@extends('dashboard.layout')
@section('title', 'Portfolio')
@section('nav')
    <a href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    @include('dashboard._requests-link')
    <a href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('freelancer.certifications.index') }}">Certifications</a>
    <a class="active" href="{{ route('freelancer.portfolio.index') }}">Portfolio</a>
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Portfolio</h1>
    <p class="sub">Show photos of your best work so employers can see what you can do.</p>

    <div class="grid g2" style="align-items:start;grid-template-columns:minmax(0,2fr) minmax(280px,1fr)">
        <div class="card">
            <h3>Your samples <span class="muted" style="font-weight:400">({{ $items->count() }}/{{ $limit }})</span></h3>
            @include('freelancers._portfolio', ['items' => $items, 'manage' => true])
        </div>

        <form class="card" method="POST" action="{{ route('freelancer.portfolio.store') }}" enctype="multipart/form-data">
            @csrf
            <h3>Add a sample</h3>
            @if ($items->count() >= $limit)
                <p class="muted">You have reached the limit of {{ $limit }} samples. Delete one to add another.</p>
            @else
                @include('freelancer.portfolio._form', ['item' => null])
                <button class="btn" style="margin-top:20px" type="submit">Upload sample</button>
            @endif
        </form>
    </div>
@endsection
