<?php

namespace App\Thrust;

use App\ThrustHelpers\Actions\QuickCreateIssue;
use BadChoice\Thrust\Fields\BelongsTo;
use BadChoice\Thrust\Fields\BelongsToMany;
use BadChoice\Thrust\Fields\Date;
use BadChoice\Thrust\Fields\Integer;
use BadChoice\Thrust\Fields\Text;
use BadChoice\Thrust\Resource;

class Issue extends Resource
{
    public static $model = \App\Issue::class;
    public static $search = ['title', 'repo', 'account', 'username'];

    public function fields()
    {
        return [
            Integer::make('issue_id')->sortable(),
            Text::make('title')->sortable(),
            BelongsToMany::make('tags'),
            BelongsTo::make('repository'),
            Text::make('username')->sortable(),
            Text::make('priority')->sortable(),
            Text::make('status')->sortable(),
            Text::make('type')->sortable(),
            Date::make('date')->sortable(),
            Date::make('created_at')->sortable(),
        ];
    }

    public function mainActions()
    {
        return [
            QuickCreateIssue::make('createIssue')
        ];
    }

    public function actions()
    {
        return [];
    }


}