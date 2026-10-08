{{--
    $items  : collection of PortfolioItem
    $manage : true -> show Edit / Delete buttons (manage page)
    Include this partial only ONCE per page (it contains the enlarge dialog).
--}}
@php $manage = $manage ?? false; @endphp

<style>
    .pf-grid{display:grid;gap:14px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))}
    .pf-item{background:#fff;border:1px solid var(--line, #cfe8c9);border-radius:12px;overflow:hidden;display:flex;flex-direction:column}
    .pf-img{display:block;aspect-ratio:4/3;background:#f4fff0;overflow:hidden}
    .pf-img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .2s}
    .pf-img:hover img{transform:scale(1.04)}
    .pf-body{padding:10px 12px 12px}
    .pf-title{margin:0;font-weight:700;color:var(--g, #0b7a0b);font-size:15px;line-height:1.3;word-break:break-word}
    .pf-desc{margin:4px 0 0;color:#567;font-size:13px;line-height:1.45;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    .pf-acts{display:flex;gap:12px;margin-top:8px;font-size:14px}
    .pf-acts form{margin:0}
    .pf-acts a,.pf-acts button{color:var(--g, #0b7a0b);font:inherit;font-weight:500;text-decoration:none;background:none;border:0;padding:0;cursor:pointer}
    .pf-acts a:hover,.pf-acts button:hover{text-decoration:underline}
    .pf-acts .del{color:#b3261e}

    .pf-dialog{border:0;border-radius:14px;padding:0;width:min(92vw,980px);max-width:92vw;overflow:hidden;background:#fff}
    .pf-dialog::backdrop{background:rgba(0,0,0,.68)}
    .pf-dialog img{display:block;max-width:100%;max-height:72vh;margin:0 auto;object-fit:contain;background:#111;width:100%}
    .pf-cap{padding:14px 18px}
    .pf-cap b{color:var(--g, #0b7a0b);font-size:17px}
    .pf-cap p{margin:6px 0 0;color:#456;line-height:1.5}
    .pf-close{position:absolute;top:10px;right:12px;width:36px;height:36px;border-radius:50%;border:0;background:rgba(0,0,0,.55);color:#fff;font-size:22px;line-height:1;cursor:pointer}
</style>

@if ($items->count())
    <div class="pf-grid">
        @foreach ($items as $item)
            <div class="pf-item">
                <a class="pf-img" href="{{ $item->file_url }}" target="_blank" rel="noopener noreferrer"
                   data-pf data-title="{{ $item->title }}" data-desc="{{ $item->description }}">
                    <img src="{{ $item->file_url }}" alt="{{ $item->title }}" loading="lazy">
                </a>
                <div class="pf-body">
                    <p class="pf-title">{{ $item->title }}</p>
                    @if ($item->description)<p class="pf-desc">{{ $item->description }}</p>@endif
                    @if ($manage)
                        <div class="pf-acts">
                            <a href="{{ route('freelancer.portfolio.edit', $item) }}">Edit</a>
                            <form method="POST" action="{{ route('freelancer.portfolio.destroy', $item) }}"
                                  onsubmit="return confirm('Delete this sample and its photo?')">
                                @csrf @method('DELETE')
                                <button class="del" type="submit">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <dialog id="pf-dialog" class="pf-dialog" aria-label="Portfolio photo">
        <button type="button" class="pf-close" aria-label="Close">&times;</button>
        <img id="pf-img" src="" alt="">
        <div class="pf-cap"><b id="pf-title"></b><p id="pf-desc"></p></div>
    </dialog>

    <script>
        (function () {
            var dlg = document.getElementById('pf-dialog');
            if (!dlg || typeof dlg.showModal !== 'function') { return; } // old browser: links just open the photo
            var img = document.getElementById('pf-img');
            var title = document.getElementById('pf-title');
            var desc = document.getElementById('pf-desc');

            document.querySelectorAll('[data-pf]').forEach(function (a) {
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    img.src = a.getAttribute('href');
                    img.alt = a.getAttribute('data-title') || '';
                    title.textContent = a.getAttribute('data-title') || '';
                    desc.textContent = a.getAttribute('data-desc') || '';
                    dlg.showModal();
                });
            });
            dlg.addEventListener('click', function (e) { if (e.target === dlg) { dlg.close(); } });
            dlg.querySelector('.pf-close').addEventListener('click', function () { dlg.close(); });
        })();
    </script>
@else
    <p class="muted" style="margin:0">No portfolio samples uploaded yet.</p>
@endif
