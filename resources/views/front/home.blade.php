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

    /* ---------- new sections ---------- */
    .band{background:#fff;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
    .wrap{max-width:1150px;margin:0 auto;padding:70px 24px}
    .sec-head{text-align:center;margin:0 auto 38px;max-width:700px}
    .sec-head h2{color:var(--g);font-size:clamp(28px,4vw,40px);margin:0 0 10px}
    .sec-head p{color:#456;font-size:18px;line-height:1.6;margin:0}

    .services{display:grid;gap:18px;grid-template-columns:repeat(auto-fill,minmax(250px,1fr))}
    .service{background:#f9fff6;border:1px solid var(--line);border-radius:16px;padding:24px;text-decoration:none;color:inherit;display:flex;flex-direction:column;transition:transform .15s,box-shadow .15s}
    .service:hover{transform:translateY(-3px);box-shadow:0 10px 26px rgba(11,122,11,.12)}
    .service .ic{width:52px;height:52px;border-radius:14px;background:var(--g2);display:grid;place-items:center;font-size:26px;margin-bottom:14px}
    .service h3{margin:0 0 6px;color:var(--g);font-size:20px}
    .service p{margin:0 0 14px;color:#456;line-height:1.55;font-size:15px}
    .service .more{margin-top:auto;color:var(--g);font-weight:700;font-size:14px}

    .rating-sum{display:inline-flex;gap:10px;align-items:center;background:var(--bg);border:1px solid var(--line);border-radius:99px;padding:8px 18px;margin-top:16px;color:var(--g);font-weight:500}
    .stars{color:#f5b301;letter-spacing:2px;font-size:18px}
    .stars .off{color:#d4dcd2}
    .reviews{display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(290px,1fr))}
    .review{background:#fff;border:1px solid var(--line);border-radius:16px;padding:24px;display:flex;flex-direction:column}
    .review blockquote{margin:12px 0 16px;color:#234;line-height:1.65;font-size:16px}
    .review .who{margin-top:auto;font-size:14px;color:#567;border-top:1px solid var(--line);padding-top:12px}
    .review .who b{color:var(--g)}
    .review .who a{color:var(--g);text-decoration:none;font-weight:500}
    .review .who a:hover{text-decoration:underline}
    .no-reviews{text-align:center;background:#f9fff6;border:1px dashed var(--g2);border-radius:16px;padding:44px 24px;color:#456}
    .no-reviews h3{margin:0 0 8px;color:var(--g)}
    .no-reviews p{margin:0 auto 18px;max-width:520px;line-height:1.6}

    .cta-band{background:var(--g);color:#fff;text-align:center}
    .cta-band .wrap{padding:60px 24px}
    .cta-band h2{margin:0 0 10px;font-size:clamp(26px,4vw,38px)}
    .cta-band p{margin:0 0 26px;color:#dcf7d8;font-size:18px}
    .cta-band .btn{margin:6px;font-size:18px;padding:14px 28px;background:#fff;color:var(--g);border-color:#fff}
    .cta-band .btn:hover{background:var(--bg)}
    .cta-band .btn.alt{background:transparent;color:#fff;border-color:#fff}
    .cta-band .btn.alt:hover{background:rgba(255,255,255,.12)}
</style>
@endpush

@section('content')
@php
    // Counts per category (live from the database)
    $counts = \App\Models\FreelancerProfile::query()
        ->whereNotNull('title')->where('title', '!=', '')
        ->selectRaw('category, count(*) as total')->groupBy('category')->pluck('total', 'category');

    // Services offered: keys must match FreelancerProfile::CATEGORIES
    $services = [
        'Home and Repair'         => ['🔧', 'Plumbing, electrical work, appliance and roof repair, painting and everyday fixes around the house.'],
        'Technology'              => ['💻', 'Phone and laptop repair, websites and apps, printer, Wi-Fi and software help.'],
        'Design and Education'    => ['🎨', 'Tarpaulins, logos and layouts, plus tutors and review classes for students.'],
        'Events'                  => ['🎉', 'Photo and video, fiesta, wedding and birthday coordination, sound system and styling.'],
        'Personal Services'       => ['💈', 'Haircuts and grooming, massage and hilot, and other services at your home.'],
        'Transportation'          => ['🛵', 'Habal-habal rides, deliveries, hauling and errands around Abuyog.'],
        'Construction'            => ['🏗️', 'Masonry, carpentry, tiling, roofing and concrete work for new builds and repairs.'],
        'Agriculture and Fishing' => ['🌾', 'Farm hands, planting and harvesting, boat operators and fresh fish supply.'],
    ];

    // Real reviews written by employers (empty until someone writes one)
    $reviews = collect();
    $reviewCount = 0;
    $reviewAvg = null;
    if (\Illuminate\Support\Facades\Schema::hasTable('reviews')) {
        $reviewCount = \App\Models\Review::count();
        $reviewAvg = $reviewCount ? round((float) \App\Models\Review::avg('rating'), 1) : null;
        $reviews = \App\Models\Review::with(['employer', 'freelancer.user'])->latest()->take(3)->get();
    }
@endphp

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
{{-- ============ SERVICES OFFERED ============ --}}
<section class="band" id="services">
    <div class="wrap">
        <div class="sec-head">
            <h2>Services offered in Abuyog</h2>
            <p>From fixing a leaking pipe to hiring a photographer for the fiesta, find skilled people for every kind of job.</p>
        </div>
        <div class="services">
            @foreach ($services as $name => [$icon, $desc])
                <a class="service" href="{{ route('freelancers.index', ['category' => $name]) }}">
                    <div class="ic">{{ $icon }}</div>
                    <h3>{{ $name }}</h3>
                    <p>{{ $desc }}</p>
                    <span class="more">{{ $counts[$name] ?? 0 }} freelancer{{ ($counts[$name] ?? 0) === 1 ? '' : 's' }} · Browse →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ REVIEWS ============ --}}
<section id="reviews">
    <div class="wrap">
        <div class="sec-head">
            <h2>What employers say</h2>
            <p>Honest reviews from people in Abuyog who hired through HireSkills.</p>
            @if ($reviewAvg)
                <div class="rating-sum">
                    <span class="stars">{{ str_repeat('★', (int) round($reviewAvg)) }}<span class="off">{{ str_repeat('★', 5 - (int) round($reviewAvg)) }}</span></span>
                    {{ number_format($reviewAvg, 1) }} out of 5 · {{ $reviewCount }} review{{ $reviewCount === 1 ? '' : 's' }}
                </div>
            @endif
        </div>

        @if ($reviews->count())
            <div class="reviews">
                @foreach ($reviews as $r)
                    <article class="review">
                        <div class="stars">{{ str_repeat('★', $r->rating) }}<span class="off">{{ str_repeat('★', 5 - $r->rating) }}</span></div>
                        <blockquote>“{{ $r->comment }}”</blockquote>
                        <div class="who">
                            <b>{{ $r->reviewer_name }}</b> reviewed
                            <a href="{{ route('freelancers.show', $r->freelancer) }}">{{ $r->freelancer->public_name }}</a>
                            @if ($r->freelancer->title) · {{ $r->freelancer->title }} @endif
                            @if ($r->freelancer->barangay) · Brgy. {{ $r->freelancer->barangay }} @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="no-reviews">
                <h3>No reviews yet</h3>
                <p>Hired someone through HireSkills? Employers can rate and review a freelancer from their profile page, and the latest reviews will show up here.</p>
                <a class="btn solid" href="{{ route('freelancers.index') }}">Browse freelancers</a>
            </div>
        @endif
    </div>
</section>

{{-- ============ CALL TO ACTION ============ --}}
<section class="cta-band">
    <div class="wrap">
        <h2>Ready to get started?</h2>
        <p>Hire a skilled neighbor today, or offer your skills to the people of Abuyog.</p>
        <a class="btn" href="{{ route('register', ['role' => 'employer']) }}">I’m looking to hire</a>
        <a class="btn alt" href="{{ route('register', ['role' => 'freelancer']) }}">I’m a freelancer</a>
    </div>
</section>
@endsection
