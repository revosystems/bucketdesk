<li id='issue_{{$issue->id}}' class="ui-state-default sort">
    <div class="float-right o60">
        <a href="{{route('thrust.edit', ['resourceName' => 'issues', 'id' => $issue->id])}}" class="showPopup">@icon(ellipsis-h)</a>
    </div>
    <div>
        <strong class="gray">
            <a class="gray" href="{{ $issue->remoteLink() }}" target="__blank">#{{$issue->issue_id}}</a>
            {{ $issue->repository->name }}
        </strong>
        {{ $issue->presenter()->priority }}
        {{ $issue->presenter()->type }}
        <div>
        {!! $issue->presenter()->tags !!}
        </div>
    </div>
    <a href="{{ route('issues.show', $issue)}}" class="showPopup">
        <p class="mt1"> {{ $issue->title }}  </p>
    </a>

</li>