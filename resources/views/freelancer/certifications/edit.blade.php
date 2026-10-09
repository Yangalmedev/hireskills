@extends('dashboard.layout')
@section('title', 'Edit Certification')
@section('nav')
    <a href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    @include('dashboard._requests-link')
    <a href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a class="active" href="{{ route('freelancer.certifications.index') }}">Certifications</a>
    <a href="{{ route('freelancer.portfolio.index') }}">Portfolio</a>
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Edit certification</h1>
    <p class="sub">Current file: <a href="{{ $certification->file_url }}" target="_blank" rel="noopener noreferrer">view certificate ↗</a></p>

    <form class="card" method="POST" action="{{ route('freelancer.certifications.update', $certification) }}" enctype="multipart/form-data" style="max-width:640px">
        @csrf @method('PUT')
        @include('freelancer.certifications._form', ['certification' => $certification, 'issuers' => $issuers])

        <div style="margin-top:22px;display:flex;gap:10px">
            <button class="btn" type="submit">Save changes</button>
            <a class="btn ghost" href="{{ route('freelancer.certifications.index') }}">Cancel</a>
        </div>
    </form>
@endsection
