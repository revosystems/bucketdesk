@extends('layout')
@section('content')
    {{--https://codepen.io/mitni455/pen/XjGLYa--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    {{--<link rel="stylesheet" href="/resources/demos/style.css">--}}

    <div class="ml4 mt2 mb-3">
        <select id='user-selector' name="username">
            @foreach ($users as $user)
                <option value="{{$user->username}}" @if($username == $user->username) selected @endif>{{$user->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="trello">
        <div class="ml4 mt4">
            <ul id="new" class="sortList">
                New
                @each('trello.card', $new, 'issue')
            </ul>

            <ul id="open" class="sortList">
                Open
                @each('trello.card', $open, 'issue')
            </ul>

            <ul id="on_hold" class="sortList">
                On Hold
                @each('trello.card', $hold, 'issue')
            </ul>

            <ul id="resolved" class="sortList">
                Resolved
                @each('trello.card', $resolved, 'issue')
            </ul>

            <br style="clear:both">
        </div>
    </div>
@stop

@section('scripts')
    <script>
        var updateUrl = "{{route("trello.update")}}";
        var csrf_token = "{{csrf_token() }}";
    </script>
    <script src="{{ asset('js/trello.js') }}"></script>
@stop
