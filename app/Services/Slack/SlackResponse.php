<?php

namespace App\Services\Slack;

use App\Issue;
use Illuminate\Support\Collection;

class SlackResponse
{
    public static function forIssue(Issue $issue)
    {
        return response()->json([
            'text'        => " Great! Issue #{$issue->issue_id} created at {$issue->repository->name}\n{$issue->title}",
            'attachments' => [
                [
                    'text'   => $issue->remoteLink(),
                    'fields' => [
                        [
                            'title' => 'Repo',
                            'value' => $issue->repository->name,
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

    public static function forIssues($title, Collection $issues)
    {
        $issuesResponse = $issues->map(function ($issue) {
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
            'text'        => $title,
            'attachments' => $issuesResponse
        ]);
    }
}