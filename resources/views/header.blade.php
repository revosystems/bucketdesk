<header>
    <img src="/images/bones.png" class="float-left mt0">
    <div class="float-left">
        <div class="link inline text-center @if (Route::current()->getName() == 'thrust.index') active @endif "><a href="{{route('thrust.index', 'issues')}}">All</a></div>
        <div class="link inline text-center @if (Route::current()->getName() == 'my.issues.current') active @endif "><a href="{{route('my.issues.current')}}">My Current Work</a></div>
        <div class="link inline text-center @if (Route::current()->getName() == 'my.issues.all') active @endif "><a href="{{route('my.issues.all')}}">All My Work</a></div>
        <div class="link inline text-center @if (Route::current()->getName() == 'trello') active @endif "><a href="{{route('trello')}}">Trello</a></div>
        <div class="link inline text-center @if (Route::current()->getName() == 'reports') active @endif "><a href="{{route('reports')}}">@icon(bar-chart)</a></div>
    </div>
    <div class="float-right white font-weight-normal mr2 mt3">
        {{--<form action="{{url('logout')}}" method="post">--}}
            {{--{{csrf_field()}}--}}
            {{--<button>{{ auth()->user()->name }}</button>--}}
        {{--</form>--}}
        {{ auth()->user()->name }}
    </div>
</header>