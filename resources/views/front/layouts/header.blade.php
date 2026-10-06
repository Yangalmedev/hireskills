<style>
    .site-header{display:flex;align-items:center;justify-content:space-between;padding:18px 56px;gap:20px;flex-wrap:wrap}
    .site-logo{font-family:Montserrat,sans-serif;font-weight:700;letter-spacing:4px;font-size:32px;color:var(--g);text-decoration:none}
    .site-brand{display:flex;flex-direction:column;line-height:1.1}
    .site-place{color:#567;font-size:13px;letter-spacing:1px;margin-top:4px}
    .site-nav{display:flex;gap:40px;flex-wrap:wrap}
    .site-nav a{text-decoration:none;color:var(--g);font-weight:500;font-size:19px}
    .site-nav a:hover,.site-nav a.on{text-decoration:underline}
    .site-actions{display:flex;gap:10px;align-items:center}
    .site-actions form{margin:0}
    @media(max-width:800px){.site-header{padding:16px 20px}.site-nav{gap:18px}.site-logo{font-size:24px}}
</style>

<header class="site-header">
    <div class="site-brand"><a class="site-logo" href="{{ route('home') }}">HIRESKILLS</a><span class="site-place">📍 Abuyog, Leyte</span></div>

    <nav class="site-nav">
        <a class="{{ request()->routeIs('home') ? 'on' : '' }}" href="{{ route('home') }}">Home</a>
        <a class="{{ request()->routeIs('about') ? 'on' : '' }}" href="{{ route('about') }}">About Us</a>
        <a class="{{ request()->routeIs('freelancers.*') ? 'on' : '' }}" href="{{ route('freelancers.index') }}">Freelancers</a>
    </nav>

    <div class="site-actions">
        @auth
            <a class="btn" href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn solid" type="submit">Log Out</button>
            </form>
        @else
            <a class="btn" href="{{ route('login') }}">Log In</a>
            <a class="btn solid" href="{{ route('register') }}">Sign Up</a>
        @endauth
    </div>
</header>
