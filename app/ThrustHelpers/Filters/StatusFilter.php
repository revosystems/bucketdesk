<?php

namespace App\ThrustHelpers\Filters;

use App\Issue;
use BadChoice\Thrust\Filters\QueryBuilder;
use BadChoice\Thrust\Filters\SelectFilter;
use Illuminate\Http\Request;

class StatusFilter extends SelectFilter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('status', $value);
    }

    public function options()
    {
        return Issue::statuses();
    }

}