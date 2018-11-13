<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Repository;

class WebhookController extends Controller
{
    public function handle()
    {
        if (request()->has('changes')) {
            $this->updateIssueFromBitbucket();
        } else {
            $this->createIssueFromBitbucket();
        }

        return response()->json(['ok' => true]);
    }

    public function updateIssueFromBitbucket(): void
    {
        $issue = Issue::where('issue_id', request('issue')['id']);
        $issue->update([
            'title'    => request('issue')['title'],
            'type'     => Issue::parseType(request('issue')['kind']),
            'priority' => Issue::parsePriority(request('issue')['priority']),
            'username' => request('issue')['assignee']['username'] ?? null,
            'status'   => Issue::parseStatus(request('issue')['state']),
        ]);
    }

    private function createIssueFromBitbucket()
    {
        $account = request('repository')['owner']['username'];
        $repo    = request('repository')['name'];
        $repository = Repository::where(['account' => $account, 'repo' => $repo])->first();
        Issue::fromBitbucketIssue($repository, (object)request('issue'));
    }
}
