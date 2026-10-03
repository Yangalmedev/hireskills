@extends('dashboard.layout')
@section('title', 'Freelancer Dashboard')
@section('nav')
    <a class="active" href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    <a href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Hi, {{ $user->name }} 👋</h1>
    <p class="sub">Manage your freelancer profile and get discovered by employers.</p>

    <div class="grid g2">
        <div class="card">
            <h3>Profile completeness</h3>
            <div class="bar"><div style="width:{{ $completeness }}%"></div></div>
            <p class="muted">{{ $completeness }}% complete — a full profile gets more clients.</p>
            <a class="btn" href="{{ route('freelancer.profile.edit') }}">Edit profile</a>
        </div>

        <div class="card">
            <div style="display:flex;gap:14px;align-items:center;margin-bottom:12px">
                <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <strong>{{ $user->name }}</strong><br>
                    <span class="muted">{{ $profile->title ?: 'Add your job title' }}</span>
                </div>
            </div>
            <p class="muted">📍 {{ collect([$profile->address, $profile->city])->filter()->implode(', ') ?: 'No address yet' }}</p>
            <p class="muted">📞 {{ $profile->phone ?: 'No phone yet' }}</p>
            <p class="muted">💰 {{ $profile->hourly_rate ? '₱'.number_format($profile->hourly_rate, 2).' / hr' : 'No rate set' }}</p>
        </div>

        <div class="card" style="grid-column:1/-1">
            <h3>Skills</h3>
            @forelse($profile->skills_list as $skill)<span class="tag">{{ $skill }}</span>@empty<p class="muted">No skills added yet.</p>@endforelse
            <h3 style="margin-top:14px">About</h3>
            <p>{{ $profile->bio ?: 'Tell employers about yourself.' }}</p>
        </div>
    </div>
@endsection
