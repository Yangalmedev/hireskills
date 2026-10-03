<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In · HireSkills Abuyog</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root{--g:#0b7a0b;--g2:#16a116;--bg:#f4fff0;--line:#cfe8c9}
        *{box-sizing:border-box}body{margin:0;font-family:Roboto,sans-serif;background:linear-gradient(#f4fff0,#fff);min-height:100vh;display:grid;place-items:center;color:#123;padding:20px}
        .card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:32px;width:100%;max-width:400px;box-shadow:0 8px 30px rgba(11,122,11,.08)}
        .logo{color:var(--g);font-weight:700;letter-spacing:3px;font-size:22px;text-decoration:none;display:block;text-align:center;margin-bottom:6px}
        h1{font-size:20px;text-align:center;color:var(--g);margin:0 0 20px}
        label{font-size:14px;font-weight:500;display:block;margin:14px 0 6px}
        input[type=email],input[type=password]{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px}
        input:focus{outline:2px solid var(--g2);border-color:transparent}
        .btn{width:100%;margin-top:20px;padding:12px;background:var(--g2);color:#fff;border:0;border-radius:8px;font-size:16px;font-weight:700;cursor:pointer}
        .btn:hover{background:var(--g)}.err{color:#c0392b;font-size:13px;margin-top:6px}
        .info{background:#e6f9e0;border:1px solid var(--g2);color:var(--g);padding:10px 14px;border-radius:8px;font-size:14px;margin-bottom:14px}
        .foot{text-align:center;font-size:14px;margin-top:18px}.foot a{color:var(--g);font-weight:500}
        .chk{display:flex;gap:8px;align-items:center;margin-top:14px;font-size:14px}
    </style>
</head>
<body>
<div class="card">
    <a class="logo" href="{{ route('home') }}">HIRESKILLS</a>
    <div style="text-align:center;color:#567;font-size:13px;margin-bottom:6px">Abuyog, Leyte</div>
    <h1>Welcome back</h1>
    @if(session('info'))<div class="info">{{ session('info') }}</div>@endif
    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')<div class="err">{{ $message }}</div>@enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <label class="chk"><input type="checkbox" name="remember"> Remember me</label>
        <button class="btn" type="submit">Log In</button>
    </form>
    <div class="foot">No account? <a href="{{ route('register') }}">Sign up</a></div>
</div>
</body>
</html>
