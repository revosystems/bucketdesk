<?php

namespace App\ThrustHelpers\Filters;

use App\Repository;
use BadChoice\Thrust\Filters\QueryBuilder;
use BadChoice\Thrust\Filters\SelectFilter;
use Illuminate\Http\Request;

class RepositoryFilter extends SelectFilter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('repository_id', $value);
    }

    public function options()
    {
        return Repository::pluck('id', 'name');
    }

}