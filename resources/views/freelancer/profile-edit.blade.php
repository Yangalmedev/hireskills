@extends('dashboard.layout')
@section('title', 'Edit Profile')
@section('nav')
    <a href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    <a class="active" href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Edit profile</h1>
    <p class="sub">This is what employers will see.</p>

    <form class="card" method="POST" action="{{ route('freelancer.profile.update') }}">
        @csrf @method('PUT')

        <label for="name">Full name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')<div class="err">{{ $message }}</div>@enderror

        <label for="title">Job title (e.g. Plumber, Photographer)</label>
        <input type="text" id="title" name="title" value="{{ old('title', $profile->title) }}">
        @error('title')<div class="err">{{ $message }}</div>@enderror

        <label for="skills">Skills (comma-separated)</label>
        <input type="text" id="skills" name="skills" placeholder="plumbing, pipe repair, installation" value="{{ old('skills', $profile->skills) }}">
        @error('skills')<div class="err">{{ $message }}</div>@enderror

        <label for="hourly_rate">Hourly rate (₱)</label>
        <input type="number" step="0.01" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}">
        @error('hourly_rate')<div class="err">{{ $message }}</div>@enderror

        <label for="bio">About you</label>
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
            <a class="btn ghost" href="{{ route('freelancer.dashboard') }}">Cancel</a>
        </div>
    </form>
@endsection
