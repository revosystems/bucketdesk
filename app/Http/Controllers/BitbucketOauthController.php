<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BitbucketOauthController extends Controller
{
    public function create()
    {
        return view('bitbucket.oauth.create');
    }

    public function store()
    {
        auth()->user()->update([
            'bitbucket_key' => request('key'),
            'bitbucket_secret' => request('secret')
        ]);
        return redirect()->route('thrust.index', 'issues');
    }
}
