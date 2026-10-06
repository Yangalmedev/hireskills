<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HireSkills Abuyog')</title>
    @include('partials.favicon')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root{--g:#0b7a0b;--g2:#16a116;--bg:#f4fff0;--line:#cfe8c9}
        *{box-sizing:border-box}
        body{margin:0;font-family:Roboto,sans-serif;color:#123;background:linear-gradient(#f4fff0,#fff 70%);min-height:100vh;display:flex;flex-direction:column}
        main{flex:1}
        a{color:var(--g)}
        .btn{display:inline-block;padding:10px 20px;border-radius:8px;border:1px solid var(--g);background:#fff;color:var(--g);text-decoration:none;font-weight:500;font-size:16px;cursor:pointer;font-family:inherit}
        .btn:hover{background:var(--bg)}
        .btn.solid{background:var(--g2);color:#fff;border-color:var(--g2);font-weight:700}
        .btn.solid:hover{background:var(--g)}
    </style>
    @stack('styles')
</head>
<body>
    @include('front.layouts.header')
    <main>
        @yield('content')
    </main>
    @include('front.layouts.footer')
</body>
</html>
