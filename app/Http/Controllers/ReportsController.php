<?php

namespace App\Http\Controllers;

use App\IssueKpi;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $report = IssueKpi::report(Carbon::now()->firstOfMonth(), Carbon::now());
        return view('reports.index', ['report' => $report]);
    }
}
