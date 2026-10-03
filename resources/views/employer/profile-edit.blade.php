@extends('dashboard.layout')
@section('title', 'Edit Profile')
@section('nav')
    <a href="{{ route('employer.dashboard') }}">Dashboard</a>
    <a href="{{ route('employer.freelancers.index') }}">Browse Freelancers</a>
    <a class="active" href="{{ route('employer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Edit profile</h1>
    <p class="sub">Tell freelancers who you are.</p>

    <form class="card" method="POST" action="{{ route('employer.profile.update') }}">
        @csrf @method('PUT')

        <label for="name">Your name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')<div class="err">{{ $message }}</div>@enderror

        <label for="company_name">Company / business name</label>
        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $profile->company_name) }}">

        <label for="bio">About</label>
        <textarea id="bio" name="bio" rows="5">{{ old('bio', $profile->bio) }}</textarea>
        @error('bio')<div class="err">{{ $message }}</div>@enderror

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $profile->phone) }}">

        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="{{ old('address', $profile->address) }}">

        <label for="city">City</label>
        <input type="text" id="city" name="city" value="{{ old('city', $profile->city) }}">

        <div style="margin-top:22px;display:flex;gap:10px">
            <button class="btn" type="submit">Save changes</button>
            <a class="btn ghost" href="{{ route('employer.dashboard') }}">Cancel</a>
        </div>
    </form>
@endsection
