@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="bb-pagination-nav">
        <ul class="bb-pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="bb-pagination-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                    <span class="bb-pagination-link" aria-hidden="true">
                        <i class="ri-arrow-left-s-line"></i>
                    </span>
                </li>
            @else
                <li class="bb-pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="bb-pagination-link" aria-label="Sebelumnya">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="bb-pagination-item disabled" aria-disabled="true">
                        <span class="bb-pagination-link dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="bb-pagination-item active" aria-current="page">
                                <span class="bb-pagination-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="bb-pagination-item">
                                <a href="{{ $url }}" class="bb-pagination-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="bb-pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="bb-pagination-link" aria-label="Berikutnya">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
            @else
                <li class="bb-pagination-item disabled" aria-disabled="true" aria-label="Berikutnya">
                    <span class="bb-pagination-link" aria-hidden="true">
                        <i class="ri-arrow-right-s-line"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
