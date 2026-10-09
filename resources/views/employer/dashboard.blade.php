@extends('dashboard.layout')
@section('title', 'Employer Dashboard')
@section('nav')
    <a class="active" href="{{ route('employer.dashboard') }}">Dashboard</a>
    @include('dashboard._requests-link')
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('employer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    @include('freelancers._card-styles')
    <h1>Hi, {{ $user->name }} 👋</h1>
    <p class="sub">Find the right talent in Abuyog for your next job.</p>

    <div class="grid g2">
        <div class="card">
            <h3>{{ $profile->company_name ?: 'Your company' }}</h3>
            <p class="muted">📍 {{ $profile->full_location }}</p>
            <p class="muted">📞 {{ $profile->phone ?: 'No phone yet' }}</p>
            <p>{{ $profile->bio ?: 'Add a short description of your company.' }}</p>
            <a class="btn ghost" href="{{ route('employer.profile.edit') }}">Edit profile</a>
        </div>
        <div class="card">
            <h3>Freelancers available</h3>
            <p style="font-size:42px;font-weight:700;color:var(--g);margin:6px 0">{{ $freelancerCount }}</p>
            <a class="btn" href="{{ route('freelancers.index') }}">Browse freelancers</a>
        </div>
    </div>

    <div class="card" style="margin-top:18px">
        <h3>Contact links</h3>
        <p class="muted" style="margin:0 0 14px">Freelancers tap these to reach you on Messenger, Gmail or by phone.</p>
        @include('freelancers._contact', ['profile' => $profile, 'mode' => 'owner'])
    </div>

    @php
        $rc = \App\Models\HireRequest::where('employer_id', $user->id)->selectRaw('status, count(*) as t')->groupBy('status')->pluck('t', 'status');
    @endphp
    <div class="card" style="margin-top:18px">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
            <h3 style="margin:0">My hire requests</h3>
            <a class="btn ghost" href="{{ route('employer.requests.index') }}">View all</a>
        </div>
        <p class="muted" style="margin:10px 0 0">Pending {{ $rc['pending'] ?? 0 }} · Accepted {{ $rc['accepted'] ?? 0 }} · Completed {{ $rc['completed'] ?? 0 }} · Declined {{ $rc['declined'] ?? 0 }}</p>
    </div>

    <h2 style="color:var(--g);margin-top:30px">Newest freelancers</h2>
    <div class="fgrid">
        @forelse($latest as $f)
            @include('freelancers._card', ['f' => $f])
        @empty
            <p class="muted">No freelancers yet.</p>
        @endforelse
    </div>
@endsection
