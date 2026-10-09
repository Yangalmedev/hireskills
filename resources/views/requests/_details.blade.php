<div class="rq-head">
    <h3 style="margin:0">{{ $req->title }}</h3>
    <span class="st {{ $req->status }}">{{ $req->status_label }}</span>
</div>
<p style="white-space:pre-line;margin:10px 0">{{ $req->description }}</p>
<div class="rq-meta">
    <span>📍 {{ $req->location_text }}</span>
    <span>📅 {{ $req->preferred_date ? $req->preferred_date->format('M d, Y') : 'Flexible date' }}</span>
    <span>💰 {{ $req->budget_text }}</span>
</div>
