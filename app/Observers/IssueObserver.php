<?php

namespace App\Observers;

use App\Issue;
use App\IssueKpi;
use Carbon\Carbon;

class IssueObserver
{
    public function created(Issue $issue)
    {
        if ($issue->status >= Issue::STATUS_CLOSED) return;

        IssueKpi::firstOrCreate([
            'type' => IssueKpi::NEW,
            'date' => Carbon::now()->toDateString()
        ])->increment('value');
    }

    /**
     * Handle the issue "updated" event.
     *
     * @param  \App\Issue  $issue
     * @return void
     */
    public function updated(Issue $issue)
    {
        if ($issue->status != Issue::STATUS_RESOLVED || $issue->getOriginal()['status'] == Issue::STATUS_RESOLVED) {
            return;
        }
        IssueKpi::firstOrCreate([
            'type'     => IssueKpi::RESOLVED,
            'date'     => Carbon::now()->toDateString(),
            'username' => $issue->username,
        ])->increment('value');
    }

    /**
     * Handle the issue "deleted" event.
     *
     * @param  \App\Issue  $issue
     * @return void
     */
    public function deleted(Issue $issue)
    {
        //
    }

    /**
     * Handle the issue "restored" event.
     *
     * @param  \App\Issue  $issue
     * @return void
     */
    public function restored(Issue $issue)
    {
        //
    }

    /**
     * Handle the issue "force deleted" event.
     *
     * @param  \App\Issue  $issue
     * @return void
     */
    public function forceDeleted(Issue $issue)
    {
        //
    }
}
