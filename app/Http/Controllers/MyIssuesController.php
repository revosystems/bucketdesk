<?php

namespace App\Http\Controllers;

use App\Issue;
use App\ThrustHelpers\Filters\StatusFilter;
use App\ThrustHelpers\Filters\UserFilter;
use BadChoice\Thrust\Controllers\ThrustController;

class MyIssuesController extends Controller
{
    public function index()
    {
        $filters = [
          StatusFilter::class => Issue::STATUS_OPEN,
          UserFilter::class   => auth()->user()->username
        ];
        request()->merge([
            'filters'    => base64_encode(http_build_query($filters)),
            'sort'       => request('sort') ?? 'priority',
            'sort_order' => request('sort_order') ??'desc',
        ]);
        return (new ThrustController)->index('issues');
    }
}
