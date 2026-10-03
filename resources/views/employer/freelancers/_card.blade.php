<div class="card">
    <div style="display:flex;gap:14px;align-items:center;margin-bottom:12px">
        <div class="avatar">{{ strtoupper(substr($f->user->name, 0, 1)) }}</div>
        <div>
            <strong>{{ $f->user->name }}</strong><br>
            <span class="muted">{{ $f->title ?: 'Freelancer' }}</span>
        </div>
    </div>
    <p class="muted">📍 {{ $f->city ?: 'Location not set' }} &nbsp;·&nbsp; {{ $f->hourly_rate ? '₱'.number_format($f->hourly_rate, 0).'/hr' : 'Rate not set' }}</p>
    <div>@foreach(array_slice($f->skills_list, 0, 3) as $s)<span class="tag">{{ $s }}</span>@endforeach</div>
    <a class="btn" style="margin-top:8px" href="{{ route('employer.freelancers.show', $f) }}">View profile</a>
</div>
