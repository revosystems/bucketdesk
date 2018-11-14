<?php

namespace App\Http\Controllers;

use App\Issue;
use App\IssueTrackers\Bitbucket\Bitbucket;

class CommentsController extends Controller
{
    public function store(Issue $issue)
    {
        $issue->comment(request('comment'));
        return back();
    }
}
