<div class="bg-broken-white p4 hidden" id="quickCreateIssue" >
    <form action="{{route('issues.store')}}" method="POST">
        {{ csrf_field() }}
        <input id='quick-create-title' name="title" placeholder="Title" class="mb2" style="width:350px;" required>
        <input id="tags" name="tags" value="{{request('tags')}}">

        <div class="mt2">
            <select name="username" style="width:350px">
                <option value="">--</option>
                @foreach (\App\User::all() as $user)
                    <option value="{{$user->username}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>

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
        <button>Create</button>
    </form>
</div>

<div class="ml4" id="quickCreateIssueButton">
    <button onclick="
            $('#quickCreateIssue').show('fast');
            $('#quickCreateIssueButton').hide('fast');
            $('#quick-create-title').focus();
        ">Create Issue</button>
</div>

@push('innerScripts')
    <script>
        $('#tags').tagsInput({
            'height': '32px',
            'width': '350px',
            {{--'autocomplete_url':'{{route('tags.index')}}',--}}
            'placeholderColor' : '#aaaaaa',
            'defaultText' : 'Add tags'
        });
    </script>
@endpush