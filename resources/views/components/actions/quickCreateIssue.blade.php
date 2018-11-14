<div class="bg-broken-white p4">
    <form action="{{route('issues.store')}}" method="POST">
        {{ csrf_field() }}
        <input name="title" placeholder="Title" class="mb2" style="width:350px;">
        <input id="tags" name="tags" value="{{request('tags')}}">
        <select name="repository_id"  class="mt2">
            @foreach ($repositories as $repository)
                <option value="{{$repository->id}}">{{$repository->name}}</option>
            @endforeach
        </select>
        <button>Create Issue</button>
    </form>
</div>

@push('innerScripts')
    <script>
        $('#tags').tagsInput({
            'height': '32px',
            'width': '350px',
        });
    </script>
@endpush