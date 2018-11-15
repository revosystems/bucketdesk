<?php

namespace App\Http\Middleware;

use App\IssueTrackers\Bitbucket\Bitbucket;
use Closure;

class NeedBitbucketOAuth
{
    public function handle($request, Closure $next)
    {
        if (! auth()->user()->bitbucket_secret || !auth()->user()->bitbucket_secret){
            return redirect()->route('bitbucket.oauth.create');
        }

        return $next($request);
    }
}
