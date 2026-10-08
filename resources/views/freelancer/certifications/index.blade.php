@extends('dashboard.layout')
@section('title', 'Certifications')
@section('nav')
    <a href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    <a href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a class="active" href="{{ route('freelancer.certifications.index') }}">Certifications</a>
    <a href="{{ route('freelancer.portfolio.index') }}">Portfolio</a>
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Certifications</h1>
    <p class="sub">Upload your TESDA, PRC or other certificates so employers can trust your skills.</p>

    <div class="grid g2" style="align-items:start">
        <div class="card">
            <h3>Your certifications <span class="muted" style="font-weight:400">({{ $certifications->count() }}/{{ $limit }})</span></h3>
            @include('freelancers._certifications', ['certifications' => $certifications, 'manage' => true])
        </div>

        <form class="card" method="POST" action="{{ route('freelancer.certifications.store') }}" enctype="multipart/form-data">
            @csrf
            <h3>Add a certification</h3>
            @if ($certifications->count() >= $limit)
                <p class="muted">You have reached the limit of {{ $limit }} certifications. Delete one to add another.</p>
            @else
                @include('freelancer.certifications._form', ['certification' => null, 'issuers' => $issuers])
                <button class="btn" style="margin-top:20px" type="submit">Upload certification</button>
            @endif
        </form>
    </div>
@endsection
