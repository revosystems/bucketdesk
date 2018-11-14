<?php

namespace App\ThrustHelpers\Filters;

use App\Repository;
use App\User;
use BadChoice\Thrust\Filters\QueryBuilder;
use BadChoice\Thrust\Filters\SelectFilter;
use Illuminate\Http\Request;

class UserFilter extends SelectFilter
{
    public function apply(Request $request, $query, $value)
    {
        if ($value == '-1'){
            return $query->whereNull('username');
        }
        return $query->where('username', $value);
    }

    public function options()
    {
        return User::pluck('username', 'name')->merge(["Unassigned" => "-1"]);
    }

}