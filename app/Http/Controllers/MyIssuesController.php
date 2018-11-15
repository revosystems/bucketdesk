<?php

namespace App\Http\Controllers;

use App\Issue;
use App\ThrustHelpers\Filters\StatusFilter;
use App\ThrustHelpers\Filters\UserFilter;
use BadChoice\Thrust\Controllers\ThrustController;

class MyIssuesController extends Controller
{
    public function current()
    {
        return $this->index([
            StatusFilter::class => Issue::STATUS_OPEN,
            UserFilter::class   => auth()->user()->username
        ]);
    }

    public function all()
    {
        return $this->index([
            //StatusFilter::class => Issue::STATUS_OPEN,
            UserFilter::class   => auth()->user()->username
        ]);
    }

    private function index($filters)
    {
        request()->merge([
            'filters'    => base64_encode(http_build_query($filters)),
            'sort'       => request('sort') ?? 'priority',
            'sort_order' => request('sort_order') ?? 'desc',
        ]);
        return (new ThrustController)->index('issues');
    }
}
