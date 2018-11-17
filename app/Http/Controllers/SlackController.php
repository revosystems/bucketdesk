<?php

namespace App\Http\Controllers;

use App\Repository;
use App\SlackCommand;
use Illuminate\Support\Facades\Log;

class SlackController extends Controller
{
    public function handle()
    {
        return $this->parseCommand(new SlackCommand, request('text'));
    }

    private function parseCommand(SlackCommand $slackCommand, $text)
    {
        Log::info("Slack command received with text: {$text}");
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
            'text'        => " Great! Issue #{$issue->issue_id} created at {$repository->name}",
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
}
