<?php

namespace App\ThrustHelpers\Metrics;

use App\Issue;
use App\IssueKpi;
use BadChoice\Thrust\Metrics\PartitionMetric;
use BadChoice\Thrust\Metrics\ValueMetric;

class SolvedIssuesCount extends ValueMetric
{
    protected $dateField = 'date';

    public function calculate()
    {
        return $this->sum(IssueKpi::where('type', IssueKpi::RESOLVED), 'value');
    }

    public function uriKey()
    {
        return 'solved-issues-count';
    }

}