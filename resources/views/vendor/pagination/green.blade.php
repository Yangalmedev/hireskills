@if ($paginator->hasPages())
<style>
    .pg-nav{display:flex;gap:6px;flex-wrap:wrap;justify-content:center;margin:26px 0}
    .pg{min-width:40px;padding:9px 14px;text-align:center;border:1px solid var(--line,#cfe8c9);border-radius:8px;background:#fff;color:var(--g,#0b7a0b);text-decoration:none;font-weight:500;font-size:15px}
    a.pg:hover{background:var(--bg,#f4fff0)}
    .pg.active{background:var(--g2,#16a116);border-color:var(--g2,#16a116);color:#fff}
    .pg.disabled{opacity:.45}
</style>
<nav class="pg-nav" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="pg disabled">‹ Prev</span>
    @else
        <a class="pg" href="{{ $paginator->previousPageUrl() }}">‹ Prev</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pg disabled">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pg active">{{ $page }}</span>
                @else
                    <a class="pg" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a class="pg" href="{{ $paginator->nextPageUrl() }}">Next ›</a>
    @else
        <span class="pg disabled">Next ›</span>
    @endif
</nav>
@endif
