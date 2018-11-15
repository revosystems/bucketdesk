<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrelloController extends Controller
{
    public function index()
    {
        return view('trello.index');
    }
}
