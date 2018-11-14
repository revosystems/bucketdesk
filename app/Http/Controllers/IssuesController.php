<?php

namespace App\Http\Controllers;

use App\Repository;

class IssuesController extends Controller
{
    public function store()
    {
        $issue = Repository::find(request('repository_id'))->createIssue(request('title'));
        $issue->attachTags(request('tags'));
        return back();
    }
}
