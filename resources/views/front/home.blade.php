@extends('front.layouts.app')

@section('title', 'HireSkills · Find skilled local talent')

@push('styles')
<style>
    .hero{text-align:center;padding:70px 20px 40px}
    .hero h1{font-size:clamp(38px,6vw,76px);line-height:1.15;color:var(--g);margin:0 0 50px;font-weight:700}
    .choose{display:flex;gap:22px;justify-content:center;flex-wrap:wrap;margin-bottom:34px}
    .choose .btn{font-size:20px;padding:18px 30px}
    .search{display:flex;max-width:700px;margin:0 auto;box-shadow:0 8px 24px rgba(11,122,11,.12);border-radius:10px}
    .search input{flex:1;padding:22px 18px;border:1px solid var(--g);border-right:0;border-radius:10px 0 0 10px;font-size:20px;background:#f7fff4;font-family:inherit}
    .search input:focus{outline:none}
    .search button{width:80px;border:1px solid var(--g);background:#f7fff4;border-radius:0 10px 10px 0;cursor:pointer;color:var(--g)}
    .search button:hover{background:var(--bg)}

    #categories{padding:30px 0 60px;overflow:hidden}
    .row{display:flex;gap:18px;width:max-content;margin-bottom:18px}
    .row.left{animation:slideLeft 40s linear infinite}
    .row.right{animation:slideRight 40s linear infinite}
    .pill{background:var(--g2);color:#fff;padding:14px 26px;border-radius:99px;font-weight:500;font-size:20px;white-space:nowrap;text-decoration:none}
    .pill:hover{background:var(--g)}
    @keyframes slideLeft{from{transform:translateX(0)}to{transform:translateX(-50%)}}
    @keyframes slideRight{from{transform:translateX(-50%)}to{transform:translateX(0)}}
    #categories:hover .row{animation-play-state:paused}
</style>
@endpush

@section('content')
@php
    $categories = [
        'Home and Repair', 'Technology', 'Design and Education', 'Events',
        'Personal Services', 'Transportation', 'Construction',
    ];
    $rowA = array_merge($categories, $categories, $categories, $categories);
    $rowB = array_reverse($rowA);
@endphp

<section class="hero">
    <h1>Find skilled local talent<br>in Abuyog, Leyte</h1>

    <div class="choose">
        <a class="btn" href="{{ route('register', ['role' => 'employer']) }}">I’m looking to hire</a>
        <a class="btn" href="{{ route('register', ['role' => 'freelancer']) }}">I’m a freelancer</a>
    </div>

    {{-- Guests are sent to log in first, then land on the results (employers only) --}}
    <form class="search" method="GET" action="{{ route('employer.freelancers.index') }}">
        <input type="text" name="q" placeholder="Try plumber or photographer">
        <button type="submit" aria-label="Search">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        </button>
    </form>
</section>

<section id="categories">
    <div class="row left">
        @foreach ($rowA as $c)
            <a class="pill" href="{{ route('employer.freelancers.index', ['q' => $c]) }}">{{ $c }}</a>
        @endforeach
    </div>
    <div class="row right">
        @foreach ($rowB as $c)
            <a class="pill" href="{{ route('employer.freelancers.index', ['q' => $c]) }}">{{ $c }}</a>
        @endforeach
    </div>
</section>
@endsection
