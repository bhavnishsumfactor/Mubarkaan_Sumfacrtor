<nav>
    @if ($paginator->hasPages())
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if (!$paginator->onFirstPage())
                <li class="page-item"><a class="page-link YK-pagination" href="{{ $paginator->url(1)  }}" rel="prev"><i class="fa fa-angle-double-left"></i></a></li>
                    <li class="page-item">
                        <a class="page-link YK-pagination" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i class="fas fa-angle-left"></i></a>
                </li>
            @endif

            @foreach(range(1, $paginator->lastPage()) as $i)
                @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 2)
                    @if ($i == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link  YK-pagination" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link YK-pagination" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><i class="fas fa-angle-right"></i></a>
                </li>
                @php $last = $paginator->lastPage(); @endphp
                <li class="page-item"><a class="page-link YK-pagination" href="{{ $paginator->url($last) }}" rel="next"><i class="fa fa-angle-double-right"></i></a></li>
            @endif
        </ul>
    @endif
</nav>