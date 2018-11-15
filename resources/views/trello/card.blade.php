<li id='{{$issue->id}}' class="ui-state-default">
    <div class="float-right o60">
        <a href="{{route('thrust.edit', ['resourceName' => 'issues', 'id' => $issue->id])}}" class="showPopup">@icon(ellipsis-h)</a>
    </div>
    <div>
        <span><a href="{{ $issue->remoteLink() }}" target="__blank">#{{$issue->issue_id}}</a></span>
        {{ \App\ThrustHelpers\Fields\PriorityField::make('priority')->displayInIndex($issue)}}
        {{ \App\ThrustHelpers\Fields\TypeField::make('type')->displayInIndex($issue)}}
        {{ $issue->repository->name }}
        {!! $issue->tags->reduce(function($carry, $tag){
            return $carry . "<span class='tag'>{$tag->name}</span>";
        }) !!}
    </div>
    <div class="gray">
        <a href="{{ route('issues.show', $issue)}}" class="showPopup">
            {{ $issue->title }}
        </a>
    </div>
</li>