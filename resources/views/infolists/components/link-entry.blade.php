<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <a href="{{$getState()}}">
        {{ $getState() }}
    </a>
</x-dynamic-component>
