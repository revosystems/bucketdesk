<form action="{{route('issues.store')}}" method="POST">
    {{ csrf_field() }}
    <input name="title">
    <select name="repository_id">
        @foreach($repositories as $repository)
            <option value="{{$repository->id}}">{{$repository->name}}</option>
        @endforeach
    </select>
    <button>Create Issue</button>
</form>
