{{-- Pagination bar. Pass :paginator="$items" to use real links; without it the original static design is shown. --}}
@props(['paginator' => null])

@if ($paginator)
    @if ($paginator->hasPages())
        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $pages = collect([1, $current - 1, $current, $current + 1, $last])
                ->filter(fn ($p) => $p >= 1 && $p <= $last)->unique()->sort()->values();
        @endphp
        <div class="pager">
            <a href="{{ $paginator->previousPageUrl() ?? '#' }}" @class(['pn', 'off' => $paginator->onFirstPage()])><x-admin.icon name="arrow-left" :size="18" /> Previous</a>
            <div class="nums">
                @foreach ($pages as $i => $page)
                    @if ($i > 0 && $page - $pages[$i - 1] > 1)<a href="#" class="off">.....</a>@endif
                    <a href="{{ $paginator->url($page) }}" @class(['on' => $page === $current])>{{ $page }}</a>
                @endforeach
            </div>
            <a href="{{ $paginator->nextPageUrl() ?? '#' }}" @class(['pn', 'off' => ! $paginator->hasMorePages()])>Next <x-admin.icon name="arrow-right" :size="18" /></a>
        </div>
    @endif
@else
    <div class="pager">
        <a href="#" class="pn"><x-admin.icon name="arrow-left" :size="18" /> Previous</a>
        <div class="nums">
            <a href="#" class="on">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
            <a href="#">.....</a>
            <a href="#">24</a>
        </div>
        <a href="#" class="pn">Next <x-admin.icon name="arrow-right" :size="18" /></a>
    </div>
@endif
