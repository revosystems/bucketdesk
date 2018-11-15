<?php

namespace App\Http\Controllers;

use App\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrelloController extends Controller
{
    public function index()
    {

        $issues = Issue::whereIn('status', [Issue::STATUS_NEW, Issue::STATUS_OPEN, Issue::STATUS_RESOLVED])->orderBy(DB::raw('`order` IS NULL, `order`'), 'asc')->get();
        return view('trello.index', [
            'new'       => $issues->where('status', Issue::STATUS_NEW),
            'open'      => $issues->where('status', Issue::STATUS_OPEN),
            'resolved'  => $issues->where('status', Issue::STATUS_RESOLVED),
        ]);
    }

    public function update()
    {
        Issue::findOrFail(request('id'))->update([
            'order'  => request('order'),
            'status' => Issue::statuses()[request('status')],
        ]);
        return response()->json('ok');
    }
}
