@extends('layout')
@section('content')
    {{--https://codepen.io/mitni455/pen/XjGLYa--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    {{--<link rel="stylesheet" href="/resources/demos/style.css">--}}
    <style>
        #sortable1, #sortable2, #sortable3 { list-style-type: none; margin: 0; float: left; margin-right: 10px; background: #eee; padding: 5px; width: 143px;}
        #sortable1 li, #sortable2 li, #sortable3 li { margin: 5px; padding: 5px; font-size: 1.2em; width: 120px; }
        .droptrue{
            min-height: 100px;
        }
    </style>


<ul id="sortable1" class="droptrue">
    <li class="ui-state-default">Can be dropped..</li>
    <li class="ui-state-default">..on an empty list</li>
    <li class="ui-state-default">Item 3</li>
    <li class="ui-state-default">Item 4</li>
    <li class="ui-state-default">Item 5</li>
</ul>

<ul id="sortable2" class="dropfalse">
    <li class="ui-state-highlight">Cannot be dropped..</li>
    <li class="ui-state-highlight">..on an empty list</li>
    <li class="ui-state-highlight">Item 3</li>
    <li class="ui-state-highlight">Item 4</li>
    <li class="ui-state-highlight">Item 5</li>
</ul>

<ul id="sortable3" class="droptrue">
</ul>

<br style="clear:both">
@stop

@section('scripts')
    <script>
        $( function() {
            $( "ul.droptrue" ).sortable({
                connectWith: "ul",
                stop: function( event, ui ) {
                    console.log("Changed");
                }
            });

            $( "ul.dropfalse" ).sortable({
                connectWith: "ul",
                dropOnEmpty: false,
            });
            $("#sortable1, #sortable2, #sortable3" ).disableSelection();
        } );
    </script>
@stop
