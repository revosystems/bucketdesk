<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Repository;
use App\Services\Slack\SlackCommand;
use App\Services\Slack\SlackResponse;
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

        return SlackResponse::forIssue($issue);
    }

    private function currentWork(SlackCommand $slackCommand, $text)
    {
        $topIssuesQuery = Issue::open()->orderBy('order', 'asc')->take(5);
        return $this->respondIssues($topIssuesQuery, $slackCommand->extractUser($text));
    }

    private function today(SlackCommand $slackCommand, $text)
    {
        $topIssuesQuery = Issue::workingOn()->orderBy('order', 'asc')->where('date', '<', Carbon::tomorrow());
        return $this->respondIssues($topIssuesQuery, $slackCommand->extractUser($text));
    }

    private function respondIssues($query, $user)
    {
        if ($user) {
            $query->where('username', $user->username);
        }

        return SlackResponse::forIssues(
            $user ? "Here you have *{$user->name}* issues" : "Here you have today's Issues",
            $query->get()
        );
    }
}
