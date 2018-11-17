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

        $repository->createIssue(trim(str_replace($repoName, '', $text)));

        return response()->json([
            'text'        => 'Issue created at revo-xef',
            'attachments' => [
                'text' => 'Awesome!'
            ]
        ]);
    }
}
