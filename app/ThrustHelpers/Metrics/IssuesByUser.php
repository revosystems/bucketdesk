<?php

namespace App\ThrustHelpers\Metrics;

use App\Issue;
use BadChoice\Thrust\Metrics\PartitionMetric;

class IssuesByUser extends PartitionMetric
{
    public function calculate()
    {
        return $this->count(Issue::where('status', '<', Issue::STATUS_RESOLVED), 'username');
    }

    public function uriKey()
    {
        return 'issues-by-user';
    }
}