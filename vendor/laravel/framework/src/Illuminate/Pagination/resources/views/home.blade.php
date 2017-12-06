<div class="page">
    @if ($paginator->onFirstPage())
        <span>上一页</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" >上一页</a>
    @endif

        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a href="#" class="active">{{ $element }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="#" class="active">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" >下一页</a>
        @else
                <span>下一页</span>
        @endif

</div>


