<?php

namespace App\ThrustHelpers\Metrics;

use App\Issue;
use App\IssueKpi;
use BadChoice\Thrust\Metrics\PartitionMetric;
use BadChoice\Thrust\Metrics\ValueMetric;

class NewIssuesCount extends ValueMetric
{
    protected $dateField = 'date';

    public function calculate()
    {
        return $this->sum(IssueKpi::where('type', IssueKpi::NEW), 'value');
    }

    public function uriKey()
    {
        return 'new-issues-count';
    }
}