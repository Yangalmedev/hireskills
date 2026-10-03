<style>
    .site-header{display:flex;align-items:center;justify-content:space-between;padding:18px 56px;gap:20px;flex-wrap:wrap}
    .site-logo{font-family:Montserrat,sans-serif;font-weight:700;letter-spacing:4px;font-size:32px;color:var(--g);text-decoration:none}
    .site-nav{display:flex;gap:40px;flex-wrap:wrap}
    .site-nav a{text-decoration:none;color:var(--g);font-weight:500;font-size:19px}
    .site-nav a:hover{text-decoration:underline}
    .site-actions{display:flex;gap:10px;align-items:center}
    .site-actions form{margin:0}
    @media(max-width:800px){.site-header{padding:16px 20px}.site-nav{gap:18px}.site-logo{font-size:24px}}
</style>

<header class="site-header">
    <a class="site-logo" href="{{ route('home') }}">HIRESKILLS</a>

    <nav class="site-nav">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About Us</a>
        <a href="{{ route('home') }}#categories">Category</a>
        <a href="{{ auth()->check() && auth()->user()->isEmployer() ? route('employer.freelancers.index') : route('register', ['role' => 'employer']) }}">Freelancers</a>
        <a href="{{ route('about') }}#contact">Contact Us</a>
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
