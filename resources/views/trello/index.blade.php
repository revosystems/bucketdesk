@extends('layout')
@section('content')
    {{--https://codepen.io/mitni455/pen/XjGLYa--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    {{--<link rel="stylesheet" href="/resources/demos/style.css">--}}

    <div class="trello">
        <div class="ml4 mt4">
            <ul id="new" class="sortList">
                @each('trello.card', $new, 'issue')
            </ul>

            <ul id="open" class="sortList">
                @each('trello.card', $open, 'issue')
            </ul>

            <ul id="resolved" class="sortList">
                @each('trello.card', $resolved, 'issue')
            </ul>

            <br style="clear:both">
        </div>
    </div>
@stop

@section('scripts')
    <script>
        var sortOptions = {
            connectWith: "ul",
            stop: function( event, ui ) {
                console.log(ui.item.index(), ui.item.attr('id').replace('issue_', ''), ui.item.parent().attr('id'));
                console.log(ui.item.parent().sortable('serialize', { key: "sort" }));
                $.ajax({
                    url : '{{route("trello.update")}}',
                    method : 'PUT',
                    data : {
                        '_token': '{{ csrf_token() }}',
                        'order': ui.item.index(),
                        'id': ui.item.attr('id').replace('issue_', ''),
                        'status': ui.item.parent().attr('id'),
                    },
                    success: function(result) {
                        console.log('issue updated');
                    },
                    error: function(request,msg,error) {
                        console.log('error updating');
                    }
                });

                $.ajax({
                    url : '{{route("trello.update")}}',
                    method : 'PUT',
                    data : {
                        '_token': '{{ csrf_token() }}',
                        'order': ui.item.index(),
                        'id': ui.item.attr('id').replace('issue_', ''),
                        'status': ui.item.parent().attr('id'),
                        'sort' : ui.item.parent().sortable('serialize', { key: "sort" }),
                    },
                    success: function(result) {
                        console.log('issue updated');
                    },
                    error: function(request,msg,error) {
                        console.log('error updating');
                    }
                });

            }
        };

        $( function() {
            $("ul.sortList").sortable(sortOptions);
            $("#new, #open, #resolved" ).disableSelection();
        } );
    </script>
@stop
