@extends('dashboard.layout')
@section('title', 'Edit Sample')
@section('nav')
    <a href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    <a href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('freelancer.certifications.index') }}">Certifications</a>
    <a class="active" href="{{ route('freelancer.portfolio.index') }}">Portfolio</a>
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Edit sample</h1>
    <p class="sub">Current photo: <a href="{{ $item->file_url }}" target="_blank" rel="noopener noreferrer">view photo ↗</a></p>

    <form class="card" method="POST" action="{{ route('freelancer.portfolio.update', $item) }}" enctype="multipart/form-data" style="max-width:640px">
        @csrf @method('PUT')
        @include('freelancer.portfolio._form', ['item' => $item])

        <div style="margin-top:22px;display:flex;gap:10px">
            <button class="btn" type="submit">Save changes</button>
            <a class="btn ghost" href="{{ route('freelancer.portfolio.index') }}">Cancel</a>
        </div>
    </form>
@endsection
