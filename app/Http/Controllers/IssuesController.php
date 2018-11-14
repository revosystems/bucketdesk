<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Repository;

class IssuesController extends Controller
{
    public function store()
    {
        $issue = Repository::find(request('repository_id'))->createIssue(request('title'));
        $issue->attachTags(request('tags'));
        return back();
    }

    public function show(Issue $issue)
    {
        return view('issues.show', [
            'issue'    => $issue,
            'remote'   => $issue->getRemote(),
            'comments' => $issue->getComments(),
        ]);
    }

    public function resolve(Issue $issue)
    {
        $issue->resolve();
        return back();
    }
}
