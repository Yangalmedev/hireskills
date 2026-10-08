@extends('dashboard.layout')
@section('title', 'Edit Profile')
@section('nav')
    <a href="{{ route('freelancer.dashboard') }}">Dashboard</a>
    <a class="active" href="{{ route('freelancer.profile.edit') }}">Edit Profile</a>
    <a href="{{ route('freelancer.certifications.index') }}">Certifications</a>
    <a href="{{ route('freelancer.portfolio.index') }}">Portfolio</a>
    <a href="{{ route('freelancers.index') }}">Browse Freelancers</a>
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

        <label for="category">Category</label>
        <select id="category" name="category">
            <option value="">— Choose a category —</option>
            @foreach($categories as $c)
                <option value="{{ $c }}" @selected(old('category', $profile->category) === $c)>{{ $c }}</option>
            @endforeach
        </select>
        @error('category')<div class="err">{{ $message }}</div>@enderror

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
        <input type="tel" id="phone" name="phone" value="{{ old('phone', $profile->phone) }}"
               inputmode="numeric" maxlength="11" pattern="09[0-9]{9}" placeholder="09171234567"
               title="11-digit mobile number starting with 09 (example: 09171234567)"
               oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11)">
        @error('phone')<div class="err">{{ $message }}</div>@enderror
        <p class="muted" style="margin:6px 0 0">Mobile number only, 11 digits, numbers only.</p>

        <div id="contact-links" style="margin-top:22px;padding-top:6px;border-top:1px solid var(--line)">
            <h3 style="margin:14px 0 0">Contact links</h3>
            <p class="muted" style="margin:4px 0 0">Employers can click these to message, email or call you. Your phone number above is linked automatically.</p>

            <label for="messenger">Facebook Messenger</label>
            <input type="text" id="messenger" name="messenger" maxlength="200"
                   value="{{ old('messenger', $profile->messenger) }}"
                   placeholder="your.username or facebook.com/your.username">
            @error('messenger')<div class="err">{{ $message }}</div>@enderror
            <p class="muted" style="margin:6px 0 0">Tip: open your Facebook profile, copy the link from the address bar and paste it here.</p>

            <label for="gmail">Gmail</label>
            <input type="text" id="gmail" name="gmail" maxlength="100" inputmode="email"
                   value="{{ old('gmail', $profile->gmail) }}" placeholder="yourname@gmail.com">
            @error('gmail')<div class="err">{{ $message }}</div>@enderror
        </div>

        <label for="address">Street / Purok / Sitio</label>
        <input type="text" id="address" name="address" value="{{ old('address', $profile->address) }}">

        <label for="barangay">Barangay</label>
        <select id="barangay" name="barangay">
            <option value="">— Choose your barangay —</option>
            @foreach(\App\Support\Abuyog::grouped() as $group => $list)
                <optgroup label="{{ $group }}">
                    @foreach($list as $b)
                        <option value="{{ $b }}" @selected(old('barangay', $profile->barangay) === $b)>{{ $b }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @error('barangay')<div class="err">{{ $message }}</div>@enderror
        <p class="muted" style="margin:6px 0 0">Municipality: Abuyog, Leyte</p>

        <div style="margin-top:22px;display:flex;gap:10px">
            <button class="btn" type="submit">Save changes</button>
            <a class="btn ghost" href="{{ route('freelancer.dashboard') }}">Cancel</a>
        </div>
    </form>
@endsection
