<?php

namespace App\Http\Controllers;

use App\Issue;
use Illuminate\Support\Facades\DB;

class TrelloController extends Controller
{
    public function index()
    {
        $username = request('username') ?? auth()->user()->username;
        $issues = Issue::where('username', $username)->whereIn('status', [Issue::STATUS_NEW, Issue::STATUS_OPEN, Issue::STATUS_RESOLVED])->orderBy(DB::raw('`order` IS NULL, `order`'), 'asc')->get();
        return view('trello.index', [
            'new'       => $issues->where('status', Issue::STATUS_NEW),
            'open'      => $issues->where('status', Issue::STATUS_OPEN),
            'resolved'  => $issues->where('status', Issue::STATUS_RESOLVED),
        ]);
    }

    public function update()
    {
        $newStatus = Issue::statuses()[request('status')];
        $issue     = Issue::findOrFail(request('id'));
        $issue->ignoreBitbucketUpdate($issue->status == $newStatus)->update([
            'status' => $newStatus,
        ]);
        $this->sortIssues();
        return response()->json('ok');
    }

    public function sortIssues()
    {
        $sortedIds = collect(explode('&', request('sort')))->map(function ($sort) {
            return explode('=', $sort)[1];
        });
        $issues = Issue::find($sortedIds);
        $sorted = array_flip($sortedIds->toArray());
        $issues->each(function ($issue) use ($sorted) {
            $issue->ignoreBitbucketUpdate()->update(['order' => $sorted[$issue->id]]);
        });
    }
}
