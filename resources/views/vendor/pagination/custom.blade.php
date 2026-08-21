@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
        </div>
        <div class="pagination">
            @if ($paginator->onFirstPage())
                <button disabled>Previous</button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"><button>Previous</button></a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}">
                    <button class="page {{ $page == $paginator->currentPage() ? 'active' : '' }}">{{ $page }}</button>
                </a>
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"><button>Next</button></a>
            @else
                <button disabled>Next</button>
            @endif
        </div>
    </div>
@endif