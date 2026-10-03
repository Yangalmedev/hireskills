@extends('dashboard.layout')
@section('title', 'Browse Freelancers')
@section('nav')
    <a href="{{ route('employer.dashboard') }}">Dashboard</a>
    <a class="active" href="{{ route('employer.freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('employer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Browse freelancers</h1>
    <p class="sub">Search by name, job title or skill.</p>

    <form class="card" method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:end;margin-bottom:22px">
        <div style="flex:2;min-width:200px"><label>Search</label><input type="text" name="q" value="{{ $q }}" placeholder="Try plumber or photographer"></div>
        <div style="flex:1;min-width:150px"><label>City</label><input type="text" name="city" value="{{ $city }}" placeholder="Any city"></div>
        <button class="btn" type="submit">Search</button>
        @if($q || $city)<a class="btn ghost" href="{{ route('employer.freelancers.index') }}">Clear</a>@endif
    </form>

    <div class="grid g3">
        @forelse($freelancers as $f)
            @include('employer.freelancers._card', ['f' => $f])
        @empty
            <p class="muted">No freelancers found.</p>
        @endforelse
    </div>

    <div class="pager">{{ $freelancers->links() }}</div>
@endsection
