@extends('front.layouts.app')

@section('title', 'About Us · HireSkills')

@push('styles')
<style>
    .about-hero{text-align:center;padding:70px 20px 30px;max-width:850px;margin:0 auto}
    .about-hero h1{font-size:clamp(34px,5vw,58px);color:var(--g);margin:0 0 16px;line-height:1.15}
    .about-hero p{font-size:20px;line-height:1.7;color:#345;margin:0}
    .sec{max-width:1050px;margin:0 auto;padding:36px 24px}
    .sec h2{color:var(--g);font-size:30px;margin:0 0 22px;text-align:center}
    .grid{display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(240px,1fr))}
    .box{background:#fff;border:1px solid var(--line);border-radius:14px;padding:26px}
    .box h3{margin:0 0 8px;color:var(--g)}
    .box p{margin:0;line-height:1.6;color:#345}
    .num{width:42px;height:42px;border-radius:50%;background:var(--g2);color:#fff;display:grid;place-items:center;font-weight:700;font-size:18px;margin-bottom:12px}
    .tags{display:flex;gap:10px;flex-wrap:wrap;justify-content:center}
    .tags a{background:var(--g2);color:#fff;padding:10px 20px;border-radius:99px;text-decoration:none;font-weight:500}
    .tags a:hover{background:var(--g)}
    .cta{text-align:center;padding:40px 20px 70px}
    .cta .btn{margin:6px;font-size:18px;padding:14px 26px}
</style>
@endpush

@section('content')
<section class="about-hero">
    <h1>Good work is closer than you think</h1>
    <p>HireSkills connects people who need a job done with skilled freelancers in their own community — plumbers, photographers, developers, drivers and more. No middlemen, just local talent you can find in minutes.</p>
</section>

<section class="sec">
    <h2>How it works</h2>
    <div class="grid">
        <div class="box"><div class="num">1</div><h3>Browse</h3><p>Search or filter freelancers by category, city and rate. Anyone can look around.</p></div>
        <div class="box"><div class="num">2</div><h3>Sign up</h3><p>Create a free account to view a freelancer’s full profile and contact details.</p></div>
        <div class="box"><div class="num">3</div><h3>Get it done</h3><p>Reach out directly, agree on the job and the price, and get to work.</p></div>
    </div>
</section>

<section class="sec">
    <h2>Built for both sides</h2>
    <div class="grid">
        <div class="box"><h3>For freelancers</h3><p>Build a profile with your skills, rate and location, and get discovered by employers nearby.</p></div>
        <div class="box"><h3>For employers</h3><p>Find reliable people for home repairs, events, tech work, deliveries and more — all in one place.</p></div>
    </div>
</section>

<section class="sec">
    <h2>What you can find</h2>
    <div class="tags">
        @foreach (\App\Models\FreelancerProfile::CATEGORIES as $c)
            <a href="{{ route('freelancers.index', ['category' => $c]) }}">{{ $c }}</a>
        @endforeach
    </div>
</section>

<section class="cta">
    <a class="btn solid" href="{{ route('register', ['role' => 'employer']) }}">I’m looking to hire</a>
    <a class="btn" href="{{ route('register', ['role' => 'freelancer']) }}">I’m a freelancer</a>
</section>
@endsection
