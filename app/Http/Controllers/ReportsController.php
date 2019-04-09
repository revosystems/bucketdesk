<?php

namespace App\Http\Controllers;

use App\IssueKpi;
use App\ThrustHelpers\Metrics\IssuesByUser;
use App\ThrustHelpers\Metrics\IssuesTypeMetric;
use App\ThrustHelpers\Metrics\NewIssuesCount;
use App\ThrustHelpers\Metrics\SolvedIssuesCount;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $report = IssueKpi::report(Carbon::now()->subDays(30), Carbon::now()->addDay());
        return view('reports.index', [
            'report' => $report,
            'metrics' => [
                new NewIssuesCount,
                new SolvedIssuesCount,
                new IssuesTypeMetric,
                new IssuesByUser,
            ]
        ]);
    }
}
