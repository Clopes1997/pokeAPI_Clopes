@if ($paginator->hasPages())
    <nav class="pagination-nav" aria-label="Navegação de páginas">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item" aria-disabled="true">
                    <span class="pagination-link pagination-link-disabled">‹</span>
                </li>
            @else
                <li class="pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev" aria-label="Anterior">‹</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pagination-item" aria-disabled="true">
                        <span class="pagination-link pagination-link-disabled">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item" aria-current="page">
                                <span class="pagination-link pagination-link-active">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $url }}" class="pagination-link" aria-label="Página {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" rel="next" aria-label="Próxima">›</a>
                </li>
            @else
                <li class="pagination-item" aria-disabled="true">
                    <span class="pagination-link pagination-link-disabled">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
