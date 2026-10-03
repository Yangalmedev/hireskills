@extends('dashboard.layout')
@section('title', 'Employer Dashboard')
@section('nav')
    <a class="active" href="{{ route('employer.dashboard') }}">Dashboard</a>
    <a href="{{ route('employer.freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('employer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Hi, {{ $user->name }} 👋</h1>
    <p class="sub">Find the right local talent for your next job.</p>

    <div class="grid g2">
        <div class="card">
            <h3>{{ $profile->company_name ?: 'Your company' }}</h3>
            <p class="muted">📍 {{ collect([$profile->address, $profile->city])->filter()->implode(', ') ?: 'No address yet' }}</p>
            <p class="muted">📞 {{ $profile->phone ?: 'No phone yet' }}</p>
            <p>{{ $profile->bio ?: 'Add a short description of your company.' }}</p>
            <a class="btn ghost" href="{{ route('employer.profile.edit') }}">Edit profile</a>
        </div>
        <div class="card">
            <h3>Freelancers available</h3>
            <p style="font-size:42px;font-weight:700;color:var(--g);margin:6px 0">{{ $freelancerCount }}</p>
            <a class="btn" href="{{ route('employer.freelancers.index') }}">Browse freelancers</a>
        </div>
    </div>

    <h2 style="color:var(--g);margin-top:30px">Newest freelancers</h2>
    <div class="grid g3">
        @forelse($latest as $f)
            @include('employer.freelancers._card', ['f' => $f])
        @empty
            <p class="muted">No freelancers yet.</p>
        @endforelse
    </div>
@endsection
