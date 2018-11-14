<?php

namespace App\ThrustHelpers\Filters;

use App\Issue;
use BadChoice\Thrust\Filters\QueryBuilder;
use BadChoice\Thrust\Filters\SelectFilter;
use Illuminate\Http\Request;

class TypeFilter extends SelectFilter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('type', $value);
    }

    public function options()
    {
        return Issue::types();
    }

}