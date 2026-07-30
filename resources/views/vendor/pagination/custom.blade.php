@if ($paginator->hasPages())
    <div class="pagination-simple">
        @if ($paginator->onFirstPage())
            <span class="disabled">
                <span class="pagination-arrow">&#10094;</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="prev">
                <span class="pagination-arrow">&#10094;</span>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="next">
                <span class="pagination-arrow">&#10095;</span>
            </a>
        @else
            <span class="disabled">
                <span class="pagination-arrow">&#10095;</span>
            </span>
        @endif
    </div>
@endif 