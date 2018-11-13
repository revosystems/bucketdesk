<?php

namespace App\Http\Controllers;

use App\Repository;

class IssuesController extends Controller
{
    public function store()
    {
        Repository::find(request('repository_id'))->createIssue(request('title'));
        return back();
    }
}
