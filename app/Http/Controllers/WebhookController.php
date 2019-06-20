<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Repository;

class WebhookController extends Controller
{
    public function handle()
    {
        Issue::fromBitbucketIssue($this->findRepository(), (object)request('issue'));

        return response()->json(['ok' => true]);
    }

    public function updateIssueFromBitbucket(): void
    {
        $repository = $this->findRepository();
        Issue::where([
            'issue_id'      => request('issue')['id'],
            'repository_id' => $repository->id
        ])->first()->update([
            'title'    => request('issue')['title'],
            'type'     => Issue::parseType(request('issue')['kind']),
            'priority' => Issue::parsePriority(request('issue')['priority']),
            'username' => request('issue')['assignee']['nickname'] ?? null,
            'status'   => Issue::parseStatus(request('issue')['state']),
        ]);
    }

    private function findRepository()
    {
        $account    = request('repository')['owner']['nickname'];
        $repo       = request('repository')['name'];
        return Repository::where(['account' => $account, 'repo' => $repo])->first();
    }
}
