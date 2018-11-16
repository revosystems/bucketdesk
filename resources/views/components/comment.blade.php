<div class="bt clear-both pt2">
    <img src="{{$avatar}}" class="circle float-left" style="height:50px; width:50px;">
    <div class="float-left" style="margin-left:20px; overflow:scroll; max-width: 85%;">
        <span class="bold">{{ $name }}</span> ∞
        <span> {{ $date->timezone('Europe/Madrid')->diffForHumans() }}</span>
        <div class="@if($editable) editable @endif">
            <p> {!! app('markdown')->convertToHtml($content) !!}</p>
        </div>
        @if ($editable)
            <a class='link editable pointer' onclick="$('.editable').hide(); $('#new-edit').show()">Edit</a>
            <div id='new-edit' class="hidden mb3">
                <form action="{{route('comments.update', $issue)}}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <textarea style="width:600px; height:100px;" name="content">{{$content}}</textarea>
                    <br>
                    <button class="secondary">Update Description</button>
                </form>
            </div>
        @endif
    </div>
</div>