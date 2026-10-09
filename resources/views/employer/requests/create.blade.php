@extends('dashboard.layout')
@section('title', 'Request to hire')
@section('nav')
    <a href="{{ route('employer.dashboard') }}">Dashboard</a>
    @include('dashboard._requests-link')
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
    <a href="{{ route('employer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Request to hire {{ $profile->user->name }}</h1>
    <p class="sub">{{ $profile->title ?: 'Freelancer' }}@if($profile->hourly_rate) · ₱{{ number_format($profile->hourly_rate, 2) }} / hr @endif</p>
    @if(session('error'))<div class="flash" style="background:#fde8e6;border-color:#c0392b;color:#c0392b">{{ session('error') }}</div>@endif

    <div class="card" style="max-width:720px">
        <form method="POST" action="{{ route('employer.hire.store', $profile) }}">
            @csrf
            <label>Job title</label>
            <input type="text" name="title" maxlength="120" value="{{ old('title') }}" placeholder="e.g. Fix leaking kitchen roof" required>
            @error('title')<div class="err">{{ $message }}</div>@enderror

            <label>Describe the job</label>
            <textarea name="description" rows="5" maxlength="1000" required>{{ old('description') }}</textarea>
            @error('description')<div class="err">{{ $message }}</div>@enderror

            <label>Barangay</label>
            <select name="barangay">
                <option value="">— Select barangay —</option>
                @foreach ($barangayGroups as $group => $list)
                    <optgroup label="{{ $group }}">
                        @foreach ($list as $b)
                            <option value="{{ $b }}" @selected(old('barangay', $defaultBarangay) === $b)>{{ $b }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('barangay')<div class="err">{{ $message }}</div>@enderror

            <label>Street / landmark (optional)</label>
            <input type="text" name="address" maxlength="150" value="{{ old('address') }}">
            @error('address')<div class="err">{{ $message }}</div>@enderror

            <label>Preferred date (optional)</label>
            <input type="date" name="preferred_date" min="{{ now()->toDateString() }}" value="{{ old('preferred_date') }}"
                   style="width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px">
            @error('preferred_date')<div class="err">{{ $message }}</div>@enderror

            <label>Budget (optional)</label>
            <div style="display:flex;gap:10px">
                <input type="number" name="budget" min="0" max="1000000" step="0.01" value="{{ old('budget') }}" placeholder="₱">
                <select name="budget_type" style="max-width:160px">
                    @foreach ($budgetTypes as $k => $v)
                        <option value="{{ $k }}" @selected(old('budget_type', 'per_job') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            @error('budget')<div class="err">{{ $message }}</div>@enderror
            @error('budget_type')<div class="err">{{ $message }}</div>@enderror

            <div style="margin-top:20px;display:flex;gap:10px">
                <button class="btn" type="submit">Send request</button>
                <a class="btn ghost" href="{{ route('freelancers.show', $profile) }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
