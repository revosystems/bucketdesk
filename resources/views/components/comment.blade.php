<div class="bt clear-both pt2">
    <img src="{{$avatar}}" class="circle float-left" style="height:50px; width:50px;">
    <div class="float-left ml4">
        <span class="bold">{{ $name }}</span> ∞
        <span> {{ $date->timezone('Europe/Madrid')->diffForHumans() }}</span>
        <p> {!! app('markdown')->convertToHtml($content) !!}</p>
    </div>
</div>