@extends('dashboard.layout')
@section('title', 'Hire Requests')
@section('nav')
    <a href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    @include('dashboard._requests-link', ['active' => true])
    <a href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('freelancer.certifications.index') }}">Certifications</a>
    <a href="{{ route('freelancer.portfolio.index') }}">Portfolio</a>
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    @include('requests._styles')
    <h1>Hire Requests</h1>
    <p class="sub">Employers in Abuyog who want to hire you.</p>
    @if(session('error'))<div class="flash" style="background:#fde8e6;border-color:#c0392b;color:#c0392b">{{ session('error') }}</div>@endif

    <div class="chips">
        <a class="chip {{ ! $status ? 'on' : '' }}" href="{{ route('freelancer.requests.index') }}">All ({{ $counts->sum() }})</a>
        @foreach (\App\Models\HireRequest::STATUSES as $s)
            <a class="chip {{ $status === $s ? 'on' : '' }}" href="{{ route('freelancer.requests.index', ['status' => $s]) }}">{{ ucfirst($s) }} ({{ $counts[$s] ?? 0 }})</a>
        @endforeach
    </div>

    @forelse ($requests as $req)
        <div class="card rq">
            <div class="muted" style="margin-bottom:8px">From <b>{{ $req->employer->name }}</b>@if($req->employer->employerProfile?->company_name) · {{ $req->employer->employerProfile->company_name }}@endif · {{ $req->created_at->diffForHumans() }}</div>
            @include('requests._details', ['req' => $req])

            @if ($req->freelancer_reply)
                <div class="rq-reply"><b>Your reply:</b> {{ $req->freelancer_reply }}</div>
            @endif

            @if ($req->status === 'pending')
                <form method="POST" action="{{ route('freelancer.requests.accept', $req) }}" style="margin-top:12px">
                    @csrf
                    <textarea name="reply" rows="2" maxlength="500" placeholder="Optional message to the employer"></textarea>
                    <div style="margin-top:10px;display:flex;gap:8px">
                        <button class="btn sm" type="submit">Accept</button>
                        <button class="btn sm red" type="submit" formaction="{{ route('freelancer.requests.decline', $req) }}">Decline</button>
                    </div>
                </form>
            @elseif (in_array($req->status, ['accepted', 'completed']))
                <div style="margin-top:14px"><b>Employer contact</b></div>
                @include('freelancers._contact', ['profile' => $req->employer->employerProfile, 'mode' => 'public', 'emptyText' => 'This employer hasn’t added contact links yet.'])
            @endif
        </div>
    @empty
        <div class="card"><p class="muted" style="margin:0">No hire requests here yet.</p></div>
    @endforelse
@endsection
