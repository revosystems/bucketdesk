<header>
    <img src="/images/bones.png" class="float-left mt0">
    <div class="float-left">
        <div class="link inline text-center @if (Route::current()->getName() == 'thrust.index') active @endif "><a href="{{route('thrust.index', 'issues')}}">All</a></div>
        <div class="link inline text-center @if (Route::current()->getName() == 'my.issues') active @endif "><a href="{{route('my.issues')}}">My Work</a></div>
    </div>
</header>