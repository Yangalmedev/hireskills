@extends('front.layouts.app')

@section('title', $profile->user->name.' · HireSkills')

@push('styles')
<style>
    .page{max-width:1000px;margin:0 auto;padding:36px 24px 60px}
    .back{color:var(--g);text-decoration:none;font-weight:500}
    .card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:26px}
    .card h3{margin:0 0 10px;color:var(--g)}
    .head{display:flex;gap:22px;align-items:center;flex-wrap:wrap;margin-top:16px}
    .av{width:90px;height:90px;border-radius:50%;background:var(--g2);color:#fff;display:grid;place-items:center;font-size:36px;font-weight:700}
    .head h1{margin:0;color:var(--g)}
    .muted{color:#678}
    .cat{display:inline-block;background:var(--bg);border:1px solid var(--line);color:var(--g);border-radius:99px;padding:3px 12px;font-size:13px;font-weight:500;margin-top:6px}
    .rate{margin-left:auto;text-align:right}
    .rate b{font-size:30px;color:var(--g)}
    .two{display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));margin-top:18px}
    .tag{display:inline-block;background:var(--g2);color:#fff;border-radius:99px;padding:5px 14px;font-size:14px;margin:0 6px 8px 0}
    .info p{margin:8px 0}
    .rev-head{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
    .stars{color:#f5b301;letter-spacing:2px;font-size:18px}
    .stars .off{color:#d4dcd2}
    .rev{border-top:1px solid var(--line);padding:16px 0}
    .rev:first-of-type{border-top:0}
    .rev p{margin:8px 0 6px;line-height:1.6}
    .flash{background:#e6f9e0;border:1px solid var(--g2);color:var(--g);padding:10px 14px;border-radius:8px;margin-bottom:14px}
    .err{color:#c0392b;font-size:13px;margin-top:6px}
    .stars-input{display:inline-flex;flex-direction:row-reverse;gap:4px}
    .stars-input input{display:none}
    .stars-input label{font-size:34px;line-height:1;color:#d4dcd2;cursor:pointer}
    .stars-input input:checked ~ label,.stars-input label:hover,.stars-input label:hover ~ label{color:#f5b301}
    .rev-form textarea{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px;font-family:inherit;margin-top:10px}
    .rev-form textarea:focus{outline:2px solid var(--g2);border-color:transparent}
</style>
@endpush

@section('content')
<div class="page">
    <a class="back" href="{{ route('freelancers.index') }}">← Back to freelancers</a>

    <div class="card head">
        <div class="av">{{ strtoupper(substr($profile->user->name, 0, 1)) }}</div>
        <div>
            <h1>{{ $profile->user->name }}</h1>
            <div class="muted">{{ $profile->title ?: 'Freelancer' }}</div>
            @if($profile->category)<span class="cat">{{ $profile->category }}</span>@endif
        </div>
        <div class="rate">
            <b>{{ $profile->hourly_rate ? '₱'.number_format($profile->hourly_rate, 2) : '—' }}</b><span class="muted"> / hr</span><br>
            @php $primary = $profile->messenger_url ?: ($profile->gmail_url ?: $profile->phone_url); @endphp
            @if ($primary)
                <a class="btn solid" style="margin-top:10px" href="{{ $primary }}"
                   @if (! str_starts_with($primary, 'tel:')) target="_blank" rel="noopener noreferrer" @endif>Contact / Hire</a>
            @endif
        </div>
    </div>

    <div class="two">
        <div class="card">
            <h3>About</h3>
            <p>{{ $profile->bio ?: 'This freelancer has not added a bio yet.' }}</p>
            <h3 style="margin-top:20px">Skills</h3>
            @forelse($profile->skills_list as $s)<span class="tag">{{ $s }}</span>@empty<p class="muted">No skills listed.</p>@endforelse
        </div>
        <div class="card info">
            <h3>Contact</h3>
            @include('freelancers._contact', ['profile' => $profile, 'mode' => 'public'])
            <p style="margin-top:16px">📍 {{ $profile->full_location }}</p>
        </div>
    </div>
    <div class="card" style="margin-top:18px" id="portfolio">
        <h3>Portfolio</h3>
        @include('freelancers._portfolio', ['items' => $profile->portfolioItems()->latest()->get()])
    </div>

    <div class="card" style="margin-top:18px" id="certifications">
        <h3>Certifications</h3>
        @include('freelancers._certifications', ['certifications' => $profile->certifications()->orderByDesc('issued_on')->get()])
    </div>

    @php
        $reviews = \Illuminate\Support\Facades\Schema::hasTable('reviews')
            ? $profile->reviews()->with('employer')->latest()->get()
            : collect();
        $avg = $reviews->count() ? round($reviews->avg('rating'), 1) : null;
        $myReview = auth()->user()?->isEmployer() ? $reviews->firstWhere('user_id', auth()->id()) : null;
    @endphp

    <div class="card" style="margin-top:18px" id="reviews">
        <div class="rev-head">
            <h3 style="margin:0">Reviews</h3>
            @if ($avg)
                <div>
                    <span class="stars">{{ str_repeat('★', (int) round($avg)) }}<span class="off">{{ str_repeat('★', 5 - (int) round($avg)) }}</span></span>
                    <b>{{ number_format($avg, 1) }}</b> <span class="muted">({{ $reviews->count() }})</span>
                </div>
            @endif
        </div>

        @if (session('success'))<div class="flash" style="margin-top:12px">{{ session('success') }}</div>@endif

        @forelse ($reviews as $r)
            <div class="rev">
                <span class="stars">{{ str_repeat('★', $r->rating) }}<span class="off">{{ str_repeat('★', 5 - $r->rating) }}</span></span>
                <p>{{ $r->comment }}</p>
                <span class="muted">{{ $r->reviewer_name }} · {{ $r->created_at->format('M d, Y') }}</span>
            </div>
        @empty
            <p class="muted" style="margin-top:12px">No reviews yet.</p>
        @endforelse

        @auth
            @if (auth()->user()->isEmployer())
                <form class="rev-form" method="POST" action="{{ route('freelancers.reviews.store', $profile) }}"
                      style="margin-top:20px;border-top:1px solid var(--line);padding-top:18px">
                    @csrf
                    <b>{{ $myReview ? 'Edit your review' : 'Write a review' }}</b>
                    <div style="margin-top:8px">
                        <div class="stars-input">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" @checked((int) old('rating', $myReview?->rating) === $i)>
                                <label for="star{{ $i }}" title="{{ $i }} star{{ $i === 1 ? '' : 's' }}">★</label>
                            @endfor
                        </div>
                    </div>
                    @error('rating')<div class="err">{{ $message }}</div>@enderror
                    <textarea name="comment" rows="4" maxlength="1000" placeholder="How was your experience working with {{ $profile->user->name }}?">{{ old('comment', $myReview?->comment) }}</textarea>
                    @error('comment')<div class="err">{{ $message }}</div>@enderror
                    <button class="btn solid" style="margin-top:12px" type="submit">{{ $myReview ? 'Update review' : 'Post review' }}</button>
                </form>
            @else
                <p class="muted" style="margin-top:16px">Only employers can write reviews.</p>
            @endif
        @endauth
    </div>
</div>
@endsection
