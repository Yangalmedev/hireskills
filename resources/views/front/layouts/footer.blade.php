<style>
    .site-footer{background:var(--g);color:#eaffea;padding:28px 56px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;font-size:15px}
    .site-footer a{color:#fff;margin-left:16px;text-decoration:none}
    .site-footer a:hover{text-decoration:underline}
    @media(max-width:800px){.site-footer{padding:22px 20px}}
</style>

<footer class="site-footer">
    <div>&copy; {{ date('Y') }} HireSkills Abuyog · Connecting skilled workers and employers in the Municipality of Abuyog, Leyte.</div>
    <div>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About Us</a>
        <a href="{{ route('freelancers.index') }}">Freelancers</a>
        @guest
            <a href="{{ route('login') }}">Log In</a>
            <a href="{{ route('register') }}">Sign Up</a>
        @endguest
    </div>
</footer>
