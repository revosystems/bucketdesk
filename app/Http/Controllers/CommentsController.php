<?php

namespace App\Http\Controllers;

use App\Issue;

class CommentsController extends Controller
{
    public function store(Issue $issue)
    {
        $issue->comment(request('comment'));
        return back();
    }

    public function update(Issue $issue)
    {
        $issue->updateBitbucketWith(['content' => request('content')]);
        return back();
    }
}
