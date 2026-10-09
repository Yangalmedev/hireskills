@extends('dashboard.layout')
@section('title', 'My Requests')
@section('nav')
    <a href="{{ route('employer.dashboard') }}">Dashboard</a>
    @include('dashboard._requests-link', ['active' => true])
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('employer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    @include('requests._styles')
    <h1>My Hire Requests</h1>
    <p class="sub">Track the freelancers you asked to hire.</p>
    @if(session('error'))<div class="flash" style="background:#fde8e6;border-color:#c0392b;color:#c0392b">{{ session('error') }}</div>@endif

    <div class="chips">
        <a class="chip {{ ! $status ? 'on' : '' }}" href="{{ route('employer.requests.index') }}">All ({{ $counts->sum() }})</a>
        @foreach (\App\Models\HireRequest::STATUSES as $s)
            <a class="chip {{ $status === $s ? 'on' : '' }}" href="{{ route('employer.requests.index', ['status' => $s]) }}">{{ ucfirst($s) }} ({{ $counts[$s] ?? 0 }})</a>
        @endforeach
    </div>

    @forelse ($requests as $req)
        @php $fp = $req->freelancer; @endphp
        <div class="card rq">
            <div class="muted" style="margin-bottom:8px">To <a href="{{ route('freelancers.show', $fp) }}"><b>{{ $fp->user->name }}</b></a> · {{ $fp->title ?: 'Freelancer' }} · {{ $req->created_at->diffForHumans() }}</div>
            @include('requests._details', ['req' => $req])

            @if ($req->freelancer_reply)
                <div class="rq-reply"><b>Reply:</b> {{ $req->freelancer_reply }}</div>
            @endif

            @if ($req->status === 'pending')
                <form method="POST" action="{{ route('employer.requests.cancel', $req) }}" style="margin-top:12px" onsubmit="return confirm('Cancel this request?')">
                    @csrf <button class="btn sm red" type="submit">Cancel request</button>
                </form>
            @elseif ($req->status === 'accepted')
                <div style="margin-top:14px"><b>Freelancer contact</b></div>
                @include('freelancers._contact', ['profile' => $fp, 'mode' => 'public'])
                <form method="POST" action="{{ route('employer.requests.complete', $req) }}" style="margin-top:12px">
                    @csrf <button class="btn sm" type="submit">Mark as completed</button>
                </form>
            @elseif ($req->status === 'completed')
                <a class="btn sm ghost" style="margin-top:12px" href="{{ route('freelancers.show', $fp) }}#reviews">Write a review</a>
            @endif
        </div>
    @empty
        <div class="card"><p class="muted" style="margin:0">No requests yet. <a href="{{ route('freelancers.index') }}">Browse freelancers</a> to send one.</p></div>
    @endforelse
@endsection
