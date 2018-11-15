@if (! $inline)
    @component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
        <input id="{{$field}}" name="{{$field}}" value="{{ $object->tagsString() }}">
    @endcomponent

    <script>
        $('#tags').tagsInput({
            'height': '32px',
            'width': '300px',
            {{--'autocomplete_url':'{{route('tags.index')}}',--}}
            'placeholderColor' : '#aaaaaa',
            'defaultText' : 'Add tags'
        });
    </script>
@endif