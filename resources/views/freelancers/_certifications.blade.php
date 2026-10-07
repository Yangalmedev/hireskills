{{--
    $certifications : collection of Certification
    $manage         : true -> show Edit / Delete buttons (manage page)
--}}
@php $manage = $manage ?? false; @endphp

<style>
    .certs{display:grid;gap:12px}
    .cert{display:flex;gap:14px;align-items:flex-start;border:1px solid var(--line, #cfe8c9);border-radius:12px;padding:14px;background:#fff}
    .cert .thumb{width:72px;height:72px;border-radius:10px;border:1px solid var(--line, #cfe8c9);background:#f4fff0;display:grid;place-items:center;overflow:hidden;flex-shrink:0;text-decoration:none;font-size:30px}
    .cert .thumb img{width:100%;height:100%;object-fit:cover;display:block}
    .cert .body{flex:1;min-width:0}
    .cert .ct{font-weight:700;color:var(--g, #0b7a0b);font-size:16px;margin:0 0 2px;word-break:break-word}
    .cert .cm{color:#567;font-size:14px;line-height:1.5}
    .cert .badge{display:inline-block;border-radius:99px;padding:2px 10px;font-size:12px;font-weight:700;margin-left:6px;vertical-align:middle}
    .cert .badge.ok{background:#e6f9e0;color:#0b7a0b}
    .cert .badge.exp{background:#fdecea;color:#b3261e}
    .cert .acts{display:flex;gap:12px;flex-wrap:wrap;margin-top:8px;font-size:14px;align-items:center}
    .cert .acts a,.cert .acts button{color:var(--g, #0b7a0b);font-weight:500;text-decoration:none;background:none;border:0;padding:0;cursor:pointer;font:inherit;font-weight:500}
    .cert .acts a:hover,.cert .acts button:hover{text-decoration:underline}
    .cert .acts .del{color:#b3261e}
    .cert .acts form{margin:0}
    @media(max-width:520px){.cert{flex-direction:column}}
</style>

@if ($certifications->count())
    <div class="certs">
        @foreach ($certifications as $c)
            <div class="cert">
                <a class="thumb" href="{{ $c->file_url }}" target="_blank" rel="noopener noreferrer" title="Open certificate">
                    @if ($c->is_image)
                        <img src="{{ $c->file_url }}" alt="Certificate: {{ $c->title }}" loading="lazy">
                    @else
                        📄
                    @endif
                </a>
                <div class="body">
                    <p class="ct">
                        {{ $c->title }}
                        @if ($c->is_expired)<span class="badge exp">Expired</span>@else<span class="badge ok">Valid</span>@endif
                    </p>
                    <div class="cm">
                        {{ $c->issuer }}<br>
                        Issued {{ $c->issued_on->format('M d, Y') }}
                        @if ($c->expires_on) · {{ $c->is_expired ? 'Expired' : 'Expires' }} {{ $c->expires_on->format('M d, Y') }} @else · No expiry @endif
                        @if ($c->credential_id)<br>Credential ID: {{ $c->credential_id }}@endif
                    </div>
                    <div class="acts">
                        <a href="{{ $c->file_url }}" target="_blank" rel="noopener noreferrer">View certificate ↗</a>
                        @if ($manage)
                            <a href="{{ route('freelancer.certifications.edit', $c) }}">Edit</a>
                            <form method="POST" action="{{ route('freelancer.certifications.destroy', $c) }}"
                                  onsubmit="return confirm('Delete this certification and its file?')">
                                @csrf @method('DELETE')
                                <button class="del" type="submit">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="muted" style="margin:0">No certifications uploaded yet.</p>
@endif
