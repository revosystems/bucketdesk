<?php

namespace App\ThrustHelpers\Metrics;
use App\Issue;
use BadChoice\Thrust\Metrics\PartitionMetric;

class IssuesTypeMetric extends PartitionMetric
{
    public function calculate()
    {
        return $this->count(Issue::where('status', '<', Issue::STATUS_RESOLVED), 'type')->names(function($value){
            return ucfirst(array_flip(Issue::types())[$value->type]);
        });
    }

    public function uriKey()
    {
        return 'issues-type';
    }
}