<li id='issue_{{$issue->id}}' class="ui-state-default sort">
    <div class="float-right o60">
        <a href="{{route('thrust.edit', ['resourceName' => 'issues', 'id' => $issue->id])}}" class="showPopup">@icon(ellipsis-h)</a>
    </div>
    <div>
        <strong class="gray">
            <a class="gray" href="{{ $issue->remoteLink() }}" target="__blank">#{{$issue->issue_id}}</a>
            {{ $issue->repository->name }}
        </strong>
        {{ \App\ThrustHelpers\Fields\PriorityField::make('priority')->displayInIndex($issue)}}
        {{ \App\ThrustHelpers\Fields\TypeField::make('type')->displayInIndex($issue)}}
        <div>
        {!! $issue->tags->reduce(function($carry, $tag){
            return $carry . "<span class='tag'>{$tag->name}</span>";
        }) !!}
        </div>
    </div>
    <a href="{{ route('issues.show', $issue)}}" class="showPopup">
        <p class="mt1"> {{ $issue->title }}  </p>
    </a>

</li>