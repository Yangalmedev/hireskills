@extends('front.layouts.app')

@section('title', 'HireSkills Abuyog · Find skilled local talent in Abuyog, Leyte')

@push('styles')
<style>
    .hero{text-align:center;padding:70px 20px 40px}
    .hero h1{font-size:clamp(38px,6vw,76px);line-height:1.15;color:var(--g);margin:0 0 14px;font-weight:700}
    .tagline{font-size:20px;color:#456;margin:0 0 40px}
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
    $categories = \App\Models\FreelancerProfile::CATEGORIES;
    $rowA = array_merge($categories, $categories, $categories, $categories);
    $rowB = array_reverse($rowA);
@endphp

<section class="hero">
    <h1>Find skilled local talent,<br>right here in Abuyog</h1>
    <p class="tagline">Serving all 63 barangays of Abuyog, Leyte</p>

    <div class="choose">
        <a class="btn" href="{{ route('register', ['role' => 'employer']) }}">I’m looking to hire</a>
        <a class="btn" href="{{ route('register', ['role' => 'freelancer']) }}">I’m a freelancer</a>
    </div>

    <form class="search" method="GET" action="{{ route('freelancers.index') }}">
        <input type="text" name="q" placeholder="Try plumber, mason or photographer in Abuyog">
        <button type="submit" aria-label="Search">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        </button>
    </form>
</section>

<section id="categories">
    <div class="row left">
        @foreach ($rowA as $c)
            <a class="pill" href="{{ route('freelancers.index', ['category' => $c]) }}">{{ $c }}</a>
        @endforeach
    </div>
    <div class="row right">
        @foreach ($rowB as $c)
            <a class="pill" href="{{ route('freelancers.index', ['category' => $c]) }}">{{ $c }}</a>
        @endforeach
    </div>
</section>
@endsection
