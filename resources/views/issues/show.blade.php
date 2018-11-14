<h2>#{{ $issue->issue_id }} {{ $issue->title }}</h2>
{{ array_flip(\App\Issue::statuses())[$issue->status] }}
{{ \App\ThrustHelpers\Fields\PriorityField::make('priority')->displayInIndex($issue)}}
{{ \App\ThrustHelpers\Fields\TypeField::make('type')->displayInIndex($issue)}}
{{ $issue->repository->name }}
<div>
    <img src="{{$remote->reported_by->avatar}}" class="circle">
    <span> {{ $remote->reported_by->display_name }}</span>
    <span> {{ Carbon\Carbon::parse($remote->utc_created_on)->timezone('Europe/Madrid')->diffForHumans() }}</span>
    <p> {{ $remote->content }}</p>
</div>

@foreach($comments as $comment)
    <div>
        <img src="{{$comment->author_info->avatar}}" class="circle">
        <span> {{ $comment->author_info->display_name }}</span>
        <span> {{ Carbon\Carbon::parse($comment->utc_created_on)->timezone('Europe/Madrid')->diffForHumans() }}</span>
        <p> {{ $comment->content }}</p>
    </div>
@endforeach
<div class="mb3 bt pt3 pb3 bb">
    <form action="{{route('comments.store', $issue)}}" method="POST">
        {{ csrf_field() }}
        <textarea name="comment" class="w100" rows="8"></textarea>
        <br>
        <button class="secondary">Comment</button>
    </form>
</div>
<div>
@if ($issue->status < \App\Issue::STATUS_RESOLVED)
    <a href="{{route('issues.resolve', $issue)}}" class="button">RESOLVE</a>
@endif
<a href="{{$issue->remoteLink()}}" target="__blank">See on Bitbucket</a>
</div>
