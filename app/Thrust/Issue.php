<?php

namespace App\Thrust;

use App\ThrustHelpers\Actions\QuickCreateIssue;
use App\ThrustHelpers\Fields\IssueLink;
use App\ThrustHelpers\Fields\PriorityField;
use App\ThrustHelpers\Fields\ResolveField;
use App\ThrustHelpers\Fields\Tags;
use App\ThrustHelpers\Fields\TypeField;
use App\ThrustHelpers\Filters\PriorityFilter;
use App\ThrustHelpers\Filters\RepositoryFilter;
use App\ThrustHelpers\Filters\StatusFilter;
use App\ThrustHelpers\Filters\TypeFilter;
use App\ThrustHelpers\Filters\UserFilter;
use BadChoice\Thrust\Fields\BelongsTo;
use BadChoice\Thrust\Fields\Date;
use BadChoice\Thrust\Fields\Gravatar;
use BadChoice\Thrust\Fields\Link;
use BadChoice\Thrust\Fields\Select;
use BadChoice\Thrust\Fields\Text;
use BadChoice\Thrust\Filters\Filter;
use BadChoice\Thrust\Resource;

class Issue extends Resource
{
    public static $model = \App\Issue::class;
    public static $search = ['title', 'repository.name', 'tags.name', 'username'];
    public static $defaultOrder = 'desc';
    public static $defaultSort = 'updated_at';

    public function fields()
    {
        return [
            IssueLink::make('issue_id')->sortable(),
            Link::make('id', 'title')->sortable()->route('issues.show')->displayCallback(function($issue){
                return $issue->title;
            })->classes('showPopup'),
            Tags::make('tags'),
            BelongsTo::make('repository')->onlyInIndex(),
            BelongsTo::make('user')->sortable()->allowNull(),
            PriorityField::make('priority')->sortable()->options(array_flip(\App\Issue::priorities())),
            TypeField::make('type')->sortable()->options(array_flip(\App\Issue::types())),
            Select::make('status')->sortable()->options(array_flip(\App\Issue::statuses())),
            Date::make('date')->sortable(),
//            Date::make('created_at')->sortable()->onlyInIndex(),

            ResolveField::make('id',''),
        ];
    }

    protected function getBaseQuery()
    {
        if ($this->filtersApplied()->keys()->contains('App\ThrustHelpers\Filters\StatusFilter') && $this->filtersApplied()['App\ThrustHelpers\Filters\StatusFilter'] != '--'){
            return parent::getBaseQuery();
        }
        return parent::getBaseQuery()->where('status', '<', \App\Issue::STATUS_RESOLVED);
    }


    public function mainActions()
    {
        return [
            QuickCreateIssue::make('createIssue'),
        ];
    }

    public function actions()
    {
        return [];
    }

    public function filters()
    {
        return [
            new PriorityFilter,
            new TypeFilter,
            new StatusFilter,
            new RepositoryFilter,
            new UserFilter,
        ];
    }

    public function canDelete($object)
    {
        return false;
    }
}