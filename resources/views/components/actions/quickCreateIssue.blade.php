<form action="{{route('issues.store')}}" method="POST">
    {{ csrf_field() }}
    <input name="title">
    <tr><td> {{ trans_choice('ticket.tag',2) }}</td><td colspan="4"> <input id="tags" name="tags" value="{{request('tags')}}"></td></tr>
    <select name="repository_id">
        @foreach($repositories as $repository)
            <option value="{{$repository->id}}">{{$repository->name}}</option>
        @endforeach
    </select>
    <button>Create Issue</button>
</form>

@push('innerScripts')
    <script>
        $('#tags').tagsInput();
    </script>
@endpush