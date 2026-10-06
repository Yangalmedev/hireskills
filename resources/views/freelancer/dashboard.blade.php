@extends('dashboard.layout')
@section('title', 'Freelancer Dashboard')
@section('nav')
    <a class="active" href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    <a href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Hi, {{ $user->name }} 👋</h1>
    <p class="sub">Manage your freelancer profile and get discovered by employers.</p>

    @if(! $profile->title)
        <div class="flash">Add a job title and category so employers can find you on the Freelancers page.</div>
    @endif

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
            <p class="muted">🗂️ {{ $profile->category ?: 'No category yet' }}</p>
            <p class="muted">📍 {{ $profile->full_location }}</p>
            <p class="muted">📞 {{ $profile->phone ?: 'No phone yet' }}</p>
            <p class="muted">💰 {{ $profile->hourly_rate ? '₱'.number_format($profile->hourly_rate, 2).' / hr' : 'No rate set' }}</p>
        </div>

        <div class="card" style="grid-column:1/-1">
            <h3>Contact links</h3>
            <p class="muted" style="margin:0 0 14px">Employers tap these to reach you on Messenger, Gmail or by phone.</p>
            @include('freelancers._contact', ['profile' => $profile, 'mode' => 'owner'])
        </div>

        <div class="card" style="grid-column:1/-1">
            <h3>Skills</h3>
            @forelse($profile->skills_list as $skill)<span class="tag">{{ $skill }}</span>@empty<p class="muted">No skills added yet.</p>@endforelse
            <h3 style="margin-top:14px">About</h3>
            <p>{{ $profile->bio ?: 'Tell employers about yourself.' }}</p>
        </div>
    </div>
@endsection
