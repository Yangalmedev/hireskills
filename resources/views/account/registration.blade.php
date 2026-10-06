<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up · HireSkills Abuyog</title>
    @include('partials.favicon')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root{--g:#0b7a0b;--g2:#16a116;--line:#cfe8c9}
        *{box-sizing:border-box}body{margin:0;font-family:Roboto,sans-serif;background:linear-gradient(#f4fff0,#fff);min-height:100vh;display:grid;place-items:center;color:#123;padding:20px}
        .card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:32px;width:100%;max-width:440px;box-shadow:0 8px 30px rgba(11,122,11,.08)}
        .logo{color:var(--g);font-weight:700;letter-spacing:3px;font-size:22px;text-decoration:none;display:block;text-align:center}
        .place{text-align:center;color:#567;font-size:13px;margin:2px 0 6px}
        h1{font-size:20px;text-align:center;color:var(--g);margin:0 0 4px}
        .lead{text-align:center;color:#567;font-size:14px;margin:0 0 18px}
        label{font-size:14px;font-weight:500;display:block;margin:14px 0 6px}
        input[type=text],input[type=email],input[type=password]{width:100%;padding:11px 12px;border:1px solid var(--line);border-radius:8px;font-size:15px}
        input:focus{outline:2px solid var(--g2);border-color:transparent}
        .roles{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .roles label{margin:0;border:1px solid var(--line);border-radius:8px;padding:12px;text-align:center;cursor:pointer;font-weight:500}
        .roles input{display:none}
        .roles label:has(input:checked){background:var(--g2);color:#fff;border-color:var(--g2)}
        .btn{width:100%;margin-top:20px;padding:12px;background:var(--g2);color:#fff;border:0;border-radius:8px;font-size:16px;font-weight:700;cursor:pointer}
        .btn:hover{background:var(--g)}.err{color:#c0392b;font-size:13px;margin-top:6px}
        .foot{text-align:center;font-size:14px;margin-top:18px}.foot a{color:var(--g);font-weight:500}
    </style>
</head>
<body>
<div class="card">
    <a class="logo" href="{{ route('home') }}">HIRESKILLS</a>
    <div class="place">Abuyog, Leyte</div>
    <h1>Create your account</h1>
    <p class="lead">Join the Abuyog community of employers and freelancers.</p>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <label>I want to</label>
        <div class="roles">
            <label><input type="radio" name="role" value="employer" @checked(old('role', $role) === 'employer')><span>Hire talent</span></label>
            <label><input type="radio" name="role" value="freelancer" @checked(old('role', $role) === 'freelancer')><span>Work as a freelancer</span></label>
        </div>
        @error('role')<div class="err">{{ $message }}</div>@enderror

        <label for="name">Full name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
        @error('name')<div class="err">{{ $message }}</div>@enderror

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @error('email')<div class="err">{{ $message }}</div>@enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @error('password')<div class="err">{{ $message }}</div>@enderror

        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>

        <button class="btn" type="submit">Sign Up</button>
    </form>
    <div class="foot">Already registered? <a href="{{ route('login') }}">Log in</a></div>
</div>
</body>
</html>
