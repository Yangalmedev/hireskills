@extends('front.layouts.app')

@section('title', 'Freelancers in Abuyog · HireSkills')

@push('styles')
@include('freelancers._card-styles')
<style>
    .page{max-width:1150px;margin:0 auto;padding:40px 24px 60px}
    .page h1{color:var(--g);font-size:40px;margin:0 0 6px}
    .sub{color:#567;margin:0 0 24px;font-size:18px}
    .notice{background:#e6f9e0;border:1px solid var(--g2);color:var(--g);padding:12px 16px;border-radius:10px;margin-bottom:22px;font-size:15px}
    .notice a{font-weight:700}
    .panel{background:#fff;border:1px solid var(--line);border-radius:14px;padding:20px;margin-bottom:22px}
    .filters{display:grid;gap:14px;grid-template-columns:2fr 1.3fr 1.3fr 1fr 1fr 1.2fr}
    .filters label{display:block;font-size:13px;font-weight:500;margin-bottom:5px;color:#345}
    .filters input,.filters select{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px;font-family:inherit;background:#fff}
    .filters input:focus,.filters select:focus{outline:2px solid var(--g2);border-color:transparent}
    .factions{display:flex;gap:10px;margin-top:16px}
    #categories{scroll-margin-top:20px;margin-bottom:24px}
    #categories h2{font-size:18px;color:var(--g);margin:0 0 12px}
    .chips{display:flex;gap:10px;flex-wrap:wrap}
    .chip{padding:9px 18px;border-radius:99px;border:1px solid var(--g2);color:var(--g);text-decoration:none;font-weight:500;background:#fff;font-size:15px}
    .chip:hover{background:var(--bg)}
    .chip.on{background:var(--g2);color:#fff}
    .chip small{opacity:.75;margin-left:4px}
    .count{color:#567;margin:0 0 16px}
    .empty{text-align:center;padding:50px 20px;background:#fff;border:1px dashed var(--line);border-radius:14px;color:#567}
    @media(max-width:1000px){.filters{grid-template-columns:1fr 1fr}}
    @media(max-width:560px){.filters{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
@php
    $active = $filters['category'];
    $keep   = request()->except('category', 'page');
    $total  = $categoryCounts->sum();
    $hasFilter = $filters['q'] || $filters['barangay'] || $active || is_numeric($filters['min']) || is_numeric($filters['max']);
@endphp

<div class="page">
    <h1>Browse freelancers in Abuyog</h1>
    <p class="sub">Find skilled people across all 63 barangays of Abuyog, Leyte — by service, barangay and rate.</p>

    @guest
        <div class="notice">
            🔒 You can browse freely. To view a freelancer’s full profile and contact details,
            <a href="{{ route('login') }}">log in</a> or <a href="{{ route('register', ['role' => 'employer']) }}">sign up</a>.
        </div>
    @endguest

    {{-- Category section --}}
    <section id="categories">
        <h2>Categories</h2>
        <div class="chips">
            <a class="chip {{ $active ? '' : 'on' }}" href="{{ route('freelancers.index', $keep) }}">All <small>{{ $total }}</small></a>
            @foreach($categories as $c)
                <a class="chip {{ $active === $c ? 'on' : '' }}"
                   href="{{ route('freelancers.index', array_merge($keep, ['category' => $c])) }}">
                    {{ $c }} <small>{{ $categoryCounts[$c] ?? 0 }}</small>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Search + filters --}}
    <form class="panel" method="GET" action="{{ route('freelancers.index') }}">
        <div class="filters">
            <div>
                <label for="q">Search</label>
                <input id="q" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Try plumber, mason or photographer">
            </div>
            <div>
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" @selected($active === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="barangay">Barangay</label>
                <select id="barangay" name="barangay">
                    <option value="">All of Abuyog</option>
                    @foreach($barangayGroups as $group => $list)
                        <optgroup label="{{ $group }}">
                            @foreach($list as $b)
                                <option value="{{ $b }}" @selected($filters['barangay'] === $b)>{{ $b }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="min_rate">Min ₱/hr</label>
                <input id="min_rate" type="number" min="0" name="min_rate" value="{{ $filters['min'] }}">
            </div>
            <div>
                <label for="max_rate">Max ₱/hr</label>
                <input id="max_rate" type="number" min="0" name="max_rate" value="{{ $filters['max'] }}">
            </div>
            <div>
                <label for="sort">Sort by</label>
                <select id="sort" name="sort">
                    <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                    <option value="rate_asc" @selected($filters['sort'] === 'rate_asc')>Rate: low to high</option>
                    <option value="rate_desc" @selected($filters['sort'] === 'rate_desc')>Rate: high to low</option>
                </select>
            </div>
        </div>
        <div class="factions">
            <button class="btn solid" type="submit">Search</button>
            @if($hasFilter)<a class="btn" href="{{ route('freelancers.index') }}">Clear filters</a>@endif
        </div>
    </form>

    <p class="count">{{ $freelancers->total() }} freelancer{{ $freelancers->total() === 1 ? '' : 's' }} found</p>

    @if($freelancers->count())
        <div class="fgrid">
            @foreach($freelancers as $f)
                @include('freelancers._card', ['f' => $f])
            @endforeach
        </div>
        {{ $freelancers->links() }}
    @else
        <div class="empty">
            <strong>No freelancers match your search.</strong><br>
            Try a different keyword or clear the filters.
        </div>
    @endif
</div>
@endsection
