@extends('front.layouts.app')

@section('title', $profile->user->name.' · HireSkills')

@push('styles')
<style>
    .page{max-width:1000px;margin:0 auto;padding:36px 24px 60px}
    .back{color:var(--g);text-decoration:none;font-weight:500}
    .card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:26px}
    .card h3{margin:0 0 10px;color:var(--g)}
    .head{display:flex;gap:22px;align-items:center;flex-wrap:wrap;margin-top:16px}
    .av{width:90px;height:90px;border-radius:50%;background:var(--g2);color:#fff;display:grid;place-items:center;font-size:36px;font-weight:700}
    .head h1{margin:0;color:var(--g)}
    .muted{color:#678}
    .cat{display:inline-block;background:var(--bg);border:1px solid var(--line);color:var(--g);border-radius:99px;padding:3px 12px;font-size:13px;font-weight:500;margin-top:6px}
    .rate{margin-left:auto;text-align:right}
    .rate b{font-size:30px;color:var(--g)}
    .two{display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));margin-top:18px}
    .tag{display:inline-block;background:var(--g2);color:#fff;border-radius:99px;padding:5px 14px;font-size:14px;margin:0 6px 8px 0}
    .info p{margin:8px 0}
</style>
@endpush

@section('content')
<div class="page">
    <a class="back" href="{{ route('freelancers.index') }}">← Back to freelancers</a>

    <div class="card head">
        <div class="av">{{ strtoupper(substr($profile->user->name, 0, 1)) }}</div>
        <div>
            <h1>{{ $profile->user->name }}</h1>
            <div class="muted">{{ $profile->title ?: 'Freelancer' }}</div>
            @if($profile->category)<span class="cat">{{ $profile->category }}</span>@endif
        </div>
        <div class="rate">
            <b>{{ $profile->hourly_rate ? '₱'.number_format($profile->hourly_rate, 2) : '—' }}</b><span class="muted"> / hr</span><br>
            <a class="btn solid" style="margin-top:10px" href="mailto:{{ $profile->user->email }}">Contact / Hire</a>
        </div>
    </div>

    <div class="two">
        <div class="card">
            <h3>About</h3>
            <p>{{ $profile->bio ?: 'This freelancer has not added a bio yet.' }}</p>
            <h3 style="margin-top:20px">Skills</h3>
            @forelse($profile->skills_list as $s)<span class="tag">{{ $s }}</span>@empty<p class="muted">No skills listed.</p>@endforelse
        </div>
        <div class="card info">
            <h3>Contact & location</h3>
            <p>✉️ {{ $profile->user->email }}</p>
            <p>📞 {{ $profile->phone ?: 'Not provided' }}</p>
            <p>📍 {{ $profile->full_location }}</p>
        </div>
    </div>
</div>
@endsection
