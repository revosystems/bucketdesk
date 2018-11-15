<form action="{{route('bitbucket.oauth.store')}}" method="POST">
    {{ csrf_field() }}
    <a href="https://bitbucket.org/account/user/{{auth()->user()->username}}/api" target="=_blank">Setup it here</a>
    <input name="key" placeholder="key">
    <input name="secret" placeholder="secret">
    <button>Save</button>
</form>