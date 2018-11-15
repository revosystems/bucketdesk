<h2>#{{ $issue->issue_id }} {{ $issue->title }}</h2>
<div class="mb2">
    {{ array_flip(\App\Issue::statuses())[$issue->status] }}
    {{ \App\ThrustHelpers\Fields\PriorityField::make('priority')->displayInIndex($issue)}}
    {{ \App\ThrustHelpers\Fields\TypeField::make('type')->displayInIndex($issue)}}
    {{ $issue->repository->name }}
    {!! $issue->tags->reduce(function($carry, $tag){
        return $carry . "<span class='tag'>{$tag->name}</span>";
    }) !!}
</div>
{{--{{ dd($remote) }}--}}
@include('components.comment', [
    'avatar' => $remote->reported_by->avatar,
    'name' => $remote->reported_by->display_name,
    'date' => Carbon\Carbon::parse($remote->utc_created_on),
    'content' => $remote->content,
])

@foreach($comments as $comment)
    @include('components.comment', [
        'avatar' => $comment->author_info->avatar,
        'name' => $comment->author_info->display_name,
        'date' => Carbon\Carbon::parse($comment->utc_created_on),
        'content' => $comment->content,
    ])
@endforeach

<div class="mb3 pt3 pb3 bb">
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
