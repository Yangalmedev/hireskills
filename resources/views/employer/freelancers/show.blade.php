@extends('dashboard.layout')
@section('title', $profile->user->name)
@section('nav')
    <a href="{{ route('employer.dashboard') }}">Dashboard</a>
    <a class="active" href="{{ route('employer.freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('employer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <a class="muted" href="{{ route('employer.freelancers.index') }}">← Back to freelancers</a>

    <div class="card" style="margin-top:14px">
        <div style="display:flex;gap:18px;align-items:center;flex-wrap:wrap">
            <div class="avatar" style="width:84px;height:84px;font-size:34px">{{ strtoupper(substr($profile->user->name, 0, 1)) }}</div>
            <div style="flex:1">
                <h1 style="margin:0">{{ $profile->user->name }}</h1>
                <p class="muted" style="margin:4px 0">{{ $profile->title ?: 'Freelancer' }}</p>
                <p class="muted" style="margin:0">📍 {{ collect([$profile->address, $profile->city])->filter()->implode(', ') ?: 'Location not set' }}</p>
            </div>
            <div style="text-align:right">
                <div style="font-size:26px;font-weight:700;color:var(--g)">{{ $profile->hourly_rate ? '₱'.number_format($profile->hourly_rate, 2) : '—' }}<span class="muted" style="font-size:14px"> / hr</span></div>
                <a class="btn" style="margin-top:8px" href="mailto:{{ $profile->user->email }}">Contact / Hire</a>
            </div>
        </div>
    </div>

    <div class="grid g2" style="margin-top:18px">
        <div class="card">
            <h3>About</h3>
            <p>{{ $profile->bio ?: 'This freelancer has not added a bio yet.' }}</p>
        </div>
        <div class="card">
            <h3>Skills</h3>
            @forelse($profile->skills_list as $s)<span class="tag">{{ $s }}</span>@empty<p class="muted">No skills listed.</p>@endforelse
            <h3 style="margin-top:14px">Contact</h3>
            <p class="muted">✉️ {{ $profile->user->email }}</p>
            <p class="muted">📞 {{ $profile->phone ?: 'Not provided' }}</p>
        </div>
    </div>
@endsection
