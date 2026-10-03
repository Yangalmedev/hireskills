@extends('front.layouts.app')

@section('title', 'About Us · HireSkills')

@push('styles')
<style>
    .wrap{max-width:900px;margin:0 auto;padding:60px 24px}
    h1{color:var(--g);font-size:42px;margin:0 0 16px}
    h2{color:var(--g);margin-top:40px}
    p{line-height:1.7;font-size:18px}
    .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:18px;margin-top:20px}
    .card{background:#fff;border:1px solid var(--line);border-radius:12px;padding:22px}
    .card h3{margin:0 0 8px;color:var(--g)}
</style>
@endpush

@section('content')
<div class="wrap">
    <h1>About HireSkills</h1>
    <p>HireSkills connects people who need a job done with skilled freelancers in their own community — plumbers, photographers, developers, drivers and more.</p>

    <div class="cards">
        <div class="card"><h3>For freelancers</h3><p>Create a profile, list your skills and rate, and get found by local employers.</p></div>
        <div class="card"><h3>For employers</h3><p>Browse freelancers, view their profiles and reach out directly to the right person.</p></div>
    </div>

    <h2 id="contact">Contact Us</h2>
    <p>Questions or feedback? Email us at <a href="mailto:hello@hireskills.test">hello@hireskills.test</a>.</p>
</div>
@endsection
