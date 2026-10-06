<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · HireSkills</title>
    @include('partials.favicon')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root{--g:#0b7a0b;--g2:#16a116;--bg:#f4fff0;--line:#cfe8c9;--txt:#123}
        *{box-sizing:border-box}
        body{margin:0;font-family:Roboto,sans-serif;background:var(--bg);color:var(--txt)}
        header{background:#fff;border-bottom:1px solid var(--line);padding:14px 28px;display:flex;align-items:center;gap:28px;flex-wrap:wrap}
        .logo{color:var(--g);font-weight:700;letter-spacing:3px;font-size:20px;text-decoration:none}
        nav{display:flex;gap:6px;flex:1;flex-wrap:wrap}
        nav a{color:var(--g);text-decoration:none;padding:8px 14px;border-radius:8px;font-weight:500}
        nav a:hover,nav a.active{background:var(--bg)}
        .who{font-size:14px;display:flex;align-items:center;gap:12px}
        .who button{background:#fff;border:1px solid var(--g);color:var(--g);padding:8px 14px;border-radius:8px;cursor:pointer;font-weight:500}
        main{max-width:1100px;margin:28px auto;padding:0 20px}
        h1{color:var(--g);margin:0 0 6px}.sub{color:#567;margin:0 0 24px}
        .grid{display:grid;gap:18px}.g3{grid-template-columns:repeat(auto-fill,minmax(280px,1fr))}.g2{grid-template-columns:repeat(auto-fit,minmax(300px,1fr))}
        .card{background:#fff;border:1px solid var(--line);border-radius:12px;padding:22px}
        .card h3{margin:0 0 10px;color:var(--g)}
        .btn{display:inline-block;background:var(--g2);color:#fff;border:0;padding:10px 18px;border-radius:8px;text-decoration:none;font-weight:700;cursor:pointer;font-size:15px}
        .btn:hover{background:var(--g)}.btn.ghost{background:#fff;color:var(--g);border:1px solid var(--g)}
        .tag{display:inline-block;background:var(--g2);color:#fff;border-radius:99px;padding:4px 12px;font-size:13px;margin:0 6px 6px 0}
        .flash{background:#e6f9e0;border:1px solid var(--g2);color:var(--g);padding:12px 16px;border-radius:8px;margin-bottom:18px}
        label{font-size:14px;font-weight:500;display:block;margin:14px 0 6px}
        input[type=text],input[type=number],textarea,select{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px;font-family:inherit}
        input:focus,textarea:focus{outline:2px solid var(--g2);border-color:transparent}
        .err{color:#c0392b;font-size:13px;margin-top:6px}
        .bar{height:10px;background:var(--line);border-radius:99px;overflow:hidden}.bar>div{height:100%;background:var(--g2)}
        .muted{color:#678;font-size:14px}.avatar{width:56px;height:56px;border-radius:50%;background:var(--g2);color:#fff;display:grid;place-items:center;font-size:22px;font-weight:700}
        table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:10px;border-bottom:1px solid var(--line);font-size:14px}
        .pager{margin-top:20px}.pager nav{display:block}
    </style>
</head>
<body>
<header>
    <a class="logo" href="{{ route('home') }}">HIRESKILLS</a>
    <nav>
        @yield('nav')
    </nav>
    <div class="who">
        <span>{{ auth()->user()->name }} <span class="muted">({{ auth()->user()->role }})</span></span>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Log Out</button></form>
    </div>
</header>
<main>
    @if(session('success'))<div class="flash">{{ session('success') }}</div>@endif
    @yield('content')
</main>
</body>
</html>
