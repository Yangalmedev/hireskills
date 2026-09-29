@extends('front.layouts.app')

@section('main')
    {{-- Hero --}}
    <section class="hero">
        <h1>Find skilled local talent,<br>right around you</h1>

        <div class="toggle">
            <button type="button">I’m looking to hire</button>
            <button type="button">I’m a freelancer</button>
        </div>

        <form class="search" action="#" method="GET">
            <input type="text" name="q" placeholder="Try plumber or photographer">
            <button type="submit" aria-label="Search">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0f7a00" stroke-width="2.4" stroke-linecap="round"><circle cx="10" cy="10" r="6"/><path d="M15 15l6 6"/></svg>
            </button>
        </form>
    </section>

    {{-- Category pills --}}
    <section class="pills">
      {{-- Category pills --}}
      <div class="ticker-container">
        <!-- TOP ROW: Slides Left -->
        <div class="ticker-row left-scroll">
            <div class="ticker-track">
                <!-- First Set -->
                <span class="tag tag-edu">Design and Education</span>
                <span class="tag tag-events">Events</span>
                <span class="tag tag-home">Home and Repair</span>
                <span class="tag tag-tech">Technology</span>
                <span class="tag tag-edu">Design and Education</span>
                <span class="tag tag-edu">Design and Education</span>
                <span class="tag tag-events">Events</span>
                <span class="tag tag-home">Home and Repair</span>
                <span class="tag tag-tech">Technology</span>
                <span class="tag tag-edu">Design and Education</span>
                <span class="tag tag-events">Events</span>
                <span class="tag tag-home">Home and Repair</span>
                <span class="tag tag-tech">Technology</span>
                <span class="tag tag-edu">Design and Education</span>
                <span class="tag tag-events">Events</span>
                <span class="tag tag-home">Home and Repair</span>
                <span class="tag tag-tech">Technology</span>
            </div>
        </div>

        <!-- BOTTOM ROW: Slides Right -->
        <div class="ticker-row right-scroll">
          <div class="ticker-track">
            <span class="tag tag-services">Personal Services</span>
            <span class="tag tag-trans">Transportation</span>
            <span class="tag tag-const">Construction</span>
            <span class="tag tag-events">Events</span>
            <span class="tag tag-services">Personal Services</span>
            <span class="tag tag-trans">Transportation</span>
            <span class="tag tag-const">Construction</span>
            <span class="tag tag-events">Events</span>
            <span class="tag tag-services">Personal Services</span>
            <span class="tag tag-trans">Transportation</span>
            <span class="tag tag-const">Construction</span>
            <span class="tag tag-events">Events</span>
            <span class="tag tag-services">Personal Services</span>
            <span class="tag tag-trans">Transportation</span>
            <span class="tag tag-const">Construction</span>
            <span class="tag tag-events">Events</span>
          </div>
        </div>
      </div>
    </section>

    {{-- Recommended --}}
    <section class="recommended">
        <h2>Recommended for you</h2>
        <p class="sub">Based on availability, location, and reviews</p>

        <div class="cards">
            @php
                $freelancers = [
                    ['name' => 'Mark Zuckerberg', 'role' => 'Photographer', 'phone' => '+639564556722', 'avatar' => '💡', 'brgy' => 'Brgy. Guintagbucan Abuyog, Leyte', 'cat' => 'Events and Programs', 'stars' => 5],
                    ['name' => 'Elon Musk', 'role' => 'Construction Worker', 'phone' => '+639564556722', 'avatar' => '📈', 'brgy' => 'Brgy. Nalibunana Abuyog, Leyte', 'cat' => 'Construction and Repair', 'stars' => 3],
                    ['name' => 'Jeff Bestos', 'role' => 'Photographer', 'phone' => '+639564556722', 'avatar' => '⚠️', 'brgy' => 'Brgy. Guintagbucan Abuyog, Leyte', 'cat' => 'Events and Programs', 'stars' => 5],
                    ['name' => 'Jeff Bestos', 'role' => 'Photographer', 'phone' => '+639564556722', 'avatar' => '⚠️', 'brgy' => 'Brgy. Guintagbucan Abuyog, Leyte', 'cat' => 'Events and Programs', 'stars' => 5],
                    ['name' => 'Mark Zuckerberg', 'role' => 'Photographer', 'phone' => '+639564556722', 'avatar' => '💡', 'brgy' => 'Brgy. Guintagbucan Abuyog, Leyte', 'cat' => 'Events and Programs', 'stars' => 5],
                    ['name' => 'Elon Musk', 'role' => 'Construction Worker', 'phone' => '+639564556722', 'avatar' => '📈', 'brgy' => 'Brgy. Nalibunana Abuyog, Leyte', 'cat' => 'Construction and Repair', 'stars' => 3],
                ];
            @endphp

            @foreach ($freelancers as $f)
                <article class="card">
                    <div class="card-head">
                        <div class="avatar">{{ $f['avatar'] }}</div>
                        <div>
                            <div class="card-name">{{ $f['name'] }}</div>
                            <div class="card-role">{{ $f['role'] }}</div>
                            <div class="card-phone">{{ $f['phone'] }}</div>
                        </div>
                    </div>

                    <div class="card-info">
                        <div>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="#000"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.500a2.5 2.5 0 0 1 0 5z"/></svg>
                            {{ $f['brgy'] }}
                        </div>
                        <div>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/></svg>
                            {{ $f['cat'] }}
                        </div>
                    </div>

                    <div class="card-foot">
                        <span class="verified">
                            <svg width="13" height="13" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#1a9b4a"/><path d="M7 12.500l3.500 3.500L17 9" fill="none" stroke="#fff" stroke-width="2.600" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Verified
                        </span>
                        <span class="stars">
                            <span class="s">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $f['stars'])★@else<span class="off">★</span>@endif
                                @endfor
                            </span>
                            {{ number_format($f['stars'] == 3 ? 5.0 : $f['stars'], 1) }}
                        </span>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="more">
            <span>Looking for more freelancers?</span>
            <button type="button">More</button>
        </div>
    </section>

    {{-- CTA + Register --}}
    <section class="lower" id="contact">
        <div class="lower-inner">
            <div class="cta">
                <h3>Find skilled local talent <em>in Abuyog, Leyte. NOW!</em></h3>
                <p>Browse skilled workers by category, location, and availability. No account needed to look around.</p>

                <div class="contact">Contact Us</div>
                <div class="socials">
                    {{-- Facebook --}}
                    <span>
                        <svg viewBox="0 0 40 40" width="35" height="35"><circle cx="20" cy="20" r="20" fill="#1877f2"/><path d="M22 32V22h3.500l.6-4H22v-2.500c0-1.200.500-2 2.100-2H26V10.200C25.600 10.100 24.400 10 23 10c-3.200 0-5 1.900-5 5.200V18h-3.500v4H18v10z" fill="#fff"/></svg>
                    </span>
                    {{-- Instagram --}}
                    <span>
                        <svg viewBox="0 0 40 40" width="35" height="35">
                            <defs><linearGradient id="ig" x1="0" y1="1" x2="1" y2="0"><stop offset="0" stop-color="#fdc830"/><stop offset=".45" stop-color="#e1306c"/><stop offset="1" stop-color="#6b3fd6"/></linearGradient></defs>
                            <rect width="40" height="40" rx="11" fill="url(#ig)"/>
                            <rect x="9" y="9" width="22" height="22" rx="7" fill="none" stroke="#fff" stroke-width="2.500"/>
                            <circle cx="20" cy="20" r="5.500" fill="none" stroke="#fff" stroke-width="2.500"/>
                            <circle cx="27" cy="13" r="1.500" fill="#fff"/>
                        </svg>
                    </span>
                    {{-- TikTok --}}
                    <span>
                        <svg viewBox="0 0 40 40" width="35" height="35">
                            <path d="M23 6h4.200c.3 3 2.300 5 5.300 5.300v4.200c-2 0-3.800-.6-5.300-1.700v9.400c0 4.700-3.600 8.300-8 8.300s-7.900-3.500-7.900-7.700c0-4.500 3.900-7.900 8.600-7.500v4.300c-2.300-.4-4.200 1.100-4.200 3.200 0 1.800 1.400 3.200 3.300 3.200s3.400-1.500 3.400-3.500V6z" fill="#25f4ee" transform="translate(-1.500 1)"/>
                            <path d="M23 6h4.200c.3 3 2.300 5 5.300 5.300v4.200c-2 0-3.800-.6-5.300-1.700v9.400c0 4.700-3.600 8.300-8 8.300s-7.900-3.500-7.900-7.700c0-4.500 3.900-7.900 8.600-7.500v4.300c-2.300-.4-4.200 1.100-4.200 3.200 0 1.800 1.400 3.200 3.300 3.200s3.400-1.500 3.400-3.500V6z" fill="#fe2c55" transform="translate(1.500 -1)"/>
                            <path d="M23 6h4.200c.3 3 2.300 5 5.300 5.300v4.200c-2 0-3.800-.6-5.300-1.700v9.400c0 4.700-3.600 8.300-8 8.300s-7.900-3.500-7.900-7.700c0-4.500 3.900-7.900 8.600-7.500v4.300c-2.300-.4-4.200 1.100-4.200 3.200 0 1.800 1.400 3.200 3.300 3.200s3.400-1.500 3.400-3.500V6z" fill="#000"/>
                        </svg>
                    </span>
                </div>
            </div>

            <form class="register" id="register" action="#" method="POST">
                @csrf
                <h4>Register</h4>
                <input type="email" name="email" placeholder="Email">
                <input type="text" name="name" placeholder="Name">
                <input type="text" name="address" placeholder="Address">

                <div class="role">
                    <button type="button">Employer</button>
                    <button type="button">Freelancer</button>
                </div>

                <input class="tight" type="text" name="address_2" placeholder="Address">
                <input class="tight" type="text" name="address_3" placeholder="Address">

                <button type="submit" class="submit">Register</button>
                <p class="login">Already have an account? <a href="#">Log In</a></p>
            </form>
        </div>
    </section>
@endsection
