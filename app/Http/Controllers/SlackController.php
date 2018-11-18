<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Repository;
use App\SlackCommand;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SlackController extends Controller
{
    public function handle()
    {
        $text = request('text');
        Log::info("Slack command received with text: {$text}");
        if (starts_with($text, 'work')) {
            return $this->currentWork(new SlackCommand, $text);
        }
        if (starts_with($text, 'today')) {
            return $this->today(new SlackCommand, $text);
        }
        return $this->parseCommand(new SlackCommand, $text);
    }

    private function parseCommand(SlackCommand $slackCommand, $text)
    {
        $repository = $slackCommand->extractRepository($text);

        if (! $repository instanceof Repository) {
            return response()->json([
                'text' => "Sorry but the repository *{$repository}* does not exist"
            ]);
        }

        $tags         = $slackCommand->extractTags($text);
        $priority     = $slackCommand->extractPriority($text);
        $status       = $slackCommand->extractStatus($text);
        $type         = $slackCommand->extractType($text);
        $user         = $slackCommand->extractUser($text);

        $issue = $repository->createIssue($text, '', [
            'responsible' => $user->username ?? null,
            'priority'    => $priority,
            'status'      => $status,
            'kind'        => $type,
        ]);
        $issue->attachTags($tags);

        return response()->json([
            'text'        => " Great! Issue #{$issue->issue_id} created at {$repository->name}\n{$issue->title}",
            'attachments' => [
                [
                    'text'   => $issue->remoteLink(),
                    'fields' => [
                        [
                            'title' => 'Repo',
                            'value' => $repository->name,
                            'short' => true
                        ],[
                            'title' => 'Issue',
                            'value' => $issue->issue_id,
                            'short' => true
                        ],[
                            'title' => 'Status',
                            'value' => $issue->presenter()->status,
                            'short' => true
                        ],[
                            'title' => 'Priority',
                            'value' => $issue->presenter()->priority,
                            'short' => true
                        ], [
                            'title' => 'Type',
                            'value' => $issue->presenter()->type,
                            'short' => true
                        ],[
                            'title' => 'Assigne',
                            'value' => $issue->username ?? '--',
                            'short' => true
                        ],
                    ]
                ]
            ]
        ]);
    }

    private function currentWork(SlackCommand $slackCommand, $text)
    {
        $topIssuesQuery = Issue::open()->orderBy('order', 'asc')->take(5);
        return $this->respondIssues($topIssuesQuery, $slackCommand->extractUser($text));
    }

    private function today(SlackCommand $slackCommand, $text)
    {
        $topIssuesQuery = Issue::open()->orderBy('order', 'asc')->where('date', '<', Carbon::tomorrow());
        return $this->respondIssues($topIssuesQuery, $slackCommand->extractUser($text));
    }

    private function respondIssues($query, $user)
    {
        if ($user) {
            $query->where('username', $user->username);
        }
        $topIssues = $query->get()->map(function ($issue) {
            return [
                'text'   => $issue->title,
                'fields' => [
                    [
                        'value' => $issue->remoteLink(),
                        'short' => false
                    ], [
                        'value' => $issue->username ?? '--',
                        'short' => false
                    ]]
            ];
        });
        return response()->json([
            'text'        => $user ? "Here you have *{$user->name}* issues" : "Here you have today's Issues",
            'attachments' => $topIssues
        ]);
    }
}
