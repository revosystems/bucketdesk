<?php

namespace App\Http\Controllers;

use App\Repository;
use Illuminate\Support\Facades\Log;

class SlackController extends Controller
{
    public function handle()
    {
        return $this->parseCommand();
    }

    private function parseCommand()
    {
        $text = request('text');
        Log::info("Slack command received with text: {$text}");

        $repoName   = explode(' ', trim($text))[0];
        $repository = Repository::where('name', $repoName)->orWhere('repo', $repoName)->first();
        if (! $repository) {
            return response()->json([
                'text' => "Sorry but the repository *{$repoName}* does not exist"
            ]);
        }

        $issue = $repository->createIssue(trim(str_replace($repoName, '', $text)));

        return response()->json([
            'text'        => " Great! Issue #{$issue->issue_id} created at {$repository->name}",
            'attachments' => [
                [
                    'text' => $issue->remoteLink(),
                    "fields" =>  [
                        [
                            "title" =>  "Repo",
                            "value" => $repository->name,
                            "short" => true
                        ],[
                            "title" =>  "Issue",
                            "value" => $issue->issue_id,
                            "short" => true
                        ],[
                            "title" =>  "Status",
                            "value" => $issue->presenter()->status,
                            "short" => true
                        ],[
                            "title" =>  "Priority",
                            "value" => $issue->presenter()->priority,
                            "short" => true
                        ], [
                            "title" =>  "Type",
                            "value" => $issue->presenter()->type,
                            "short" => true
                        ],
                    ]
                ]
            ]
        ]);
    }
}
