<div class="bg-broken-white p4 hidden" id="quickCreateIssue" >
    <form action="{{route('issues.store')}}" method="POST">
        {{ csrf_field() }}
        <input name="title" placeholder="Title" class="mb2" style="width:350px;" required>
        <input id="tags" name="tags" value="{{request('tags')}}">
        {{--<select name="status" style="width:100px">>--}}
            {{--@foreach(\App\Issue::statuses() as $name => $value)--}}
                {{--<option value="{{$value}}">{{$name}}</option>--}}
            {{--@endforeach--}}
        {{--</select>--}}
        <select name="type" style="width:100px">
            @foreach(\App\Issue::types() as $name => $value)
                <option value="{{$value}}" >{{$name}}</option>
            @endforeach
        </select>
        <select name="priority" style="width:100px">>
            @foreach(\App\Issue::priorities() as $name => $value)
                <option value="{{$value}}" @if($value == \App\Issue::PRIORITY_MAJOR) selected @endif >{{$name}}</option>
            @endforeach
        </select>
        <select name="repository_id"  class="mt2" style="width:100px">>
            @foreach ($repositories as $repository)
                <option value="{{$repository->id}}">{{$repository->name}}</option>
            @endforeach
        </select>
        <button>Create Issue</button>
    </form>
</div>

<div class="ml4" id="quickCreateIssueButton">
    <button onclick="$('#quickCreateIssue').show('fast'); $('#quickCreateIssueButton').hide('fast')">Create Issue</button>
</div>

@push('innerScripts')
    <script>
        $('#tags').tagsInput({
            'height': '32px',
            'width': '350px',
        });
    </script>
@endpush