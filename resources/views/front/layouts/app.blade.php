<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caacupe+One&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
      :root {
          --green-dark: #0f7a00;
          --green: #13a300;
          --green-bright: #22c400;
          --green-border: #2f8f1f;
          --green-soft: #eafde1;
          --text-muted: #7a7a7a;
          --star: #f5a623;
      }
      * { box-sizing: border-box; margin: 0; padding: 0; }
      html { scroll-behavior: smooth; }
      body {
          font-family: 'Roboto', Arial, sans-serif;
          color: var(--green-dark);
          background: linear-gradient(180deg, #f9fff6 0%, #e9fddb 45%, #d3f7b0 100%);
          min-height: 100vh;
          overflow-x: hidden;
      }
      a { color: inherit; text-decoration: none; }
      button { font-family: inherit; cursor: pointer; }

      /* ---------- Navbar ---------- */
      .navbar {
          display: flex; align-items: center; justify-content: space-between;
          padding: 20px 36px;
      }
      .logo {
          font-family: 'Montserrat', 'Roboto', sans-serif;
          font-weight: 700; font-size: 25px; letter-spacing: 1px;
          color: var(--green-dark);
      }
      .nav-links { display: flex; gap: 30px; font-size: 16px; font-weight: 500; }
      .nav-actions { display: flex; gap: 6px; }
      .btn-outline, .btn-solid {
          width: 81px; height: 35px; border-radius: 6px;
          font-size: 15px; font-weight: 700;
          display: inline-flex; align-items: center; justify-content: center;
      }
      .btn-outline { background: #fff; color: var(--green-dark); border: 1px solid var(--green-border); }
      .btn-solid { background: var(--green); color: #fff; border: 1px solid var(--green-dark); }

      /* ---------- Hero ---------- */
      .hero { text-align: center; padding: 62px 20px 0; }
      .hero h1 { font-size: 60px; line-height: 1.17; font-weight: 700; color: var(--green-dark); }
      .toggle { display: flex; justify-content: center; gap: 15px; margin-top: 62px; }
      .toggle button {
          border-radius: 6px; padding: 10px 20px; align-items: center; 
          background: #f4fdef; color: var(--green-dark);
          border: 1px solid var(--green-border); font-size: 15px;
      }
      .toggle button:hover { background: var(--green); color: var(--green-soft)}
      .toggle button.active { background: var(--green); }
      .search {
          display: flex; justify-content: center; margin: 32px auto 0; width: min(455px, 90%); box-shadow: 0 6px 14px rgba(0,0,0,.18);
      }
      .search input {
          flex: 1; height: 50px; padding: 0 10px; font-size: 15px; font-family: inherit;
          border: 1px solid #1b8107; border-right: none; border-radius: 6px 0 0 6px;
          background: #f4fdef; color: var(--green-dark); outline: none;
      }
      .search input::placeholder { color: #9aa79a; }
      .search button {
          width: 52px; height: 50px; background: #f4fdef;
          border: 1px solid #5fb44d; border-radius: 6px;
          box-shadow: 0 2px 5px rgba(0,0,0,.2);
          display: flex; align-items: center; justify-content: center;
      }

      /* ---------- Category pills ---------- */
      .pills { margin-top: 62px;  justify-content: center}
      .ticker-container {
        overflow: hidden;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 12px; /* Gap between top and bottom rows */
        padding: 20px 0;
      }

      .ticker-row {
          display: flex;
          width: 100%;
          overflow: hidden;
      }

      /* The track holding the tags. Must be twice as wide as a single set of items */
      .ticker-track {
          display: flex;
          gap: 10px; /* Space between pill buttons */
          white-space: nowrap;
          width: max-content;
      }

      /* Base design for your category pill shapes */
      .tag {
          display: inline-block;
          padding: 8px 16px;
          border-radius: 20px;
          color: white;
          font-size: 14px;
          font-weight: 500;
          background-color: #008f24;
      }

      /* --- ANIMATION LOGIC --- */

      .left-scroll .ticker-track { animation: scroll-left 15s linear infinite;}
      .right-scroll .ticker-track { animation: scroll-right 15s linear infinite; }

      /* Pause the scrolling when a user hovers over a category */
      .ticker-track:hover {animation-play-state: paused;}

      /* Keyframes: Move by exactly half the container width (-50%) to loop perfectly */
      @keyframes scroll-left {
          0% {
              transform: translateX(0);
          }
          100% {
              transform: translateX(-50%);
          }
      }
      @keyframes scroll-right {
          0% {
              transform: translateX(-50%);
          }
          100% {
              transform: translateX(0);
          }
      }


      /* .pill-row { display: flex; gap: 10px; white-space: nowrap; margin-bottom: 14px; width: max-content; }
      .pill-row.one { margin-left: -60px; }
      .pill-row.two { margin-left: 11px; }
      .pill {
          background: var(--green); color: #fff; font-size: 12.5px;
          height: 25px; padding: 0 14px; border-radius: 999px;
          display: inline-flex; align-items: center;
      } */

      /* ---------- Recommended ---------- */
      .recommended { padding: 68px 21px 0; }
      .recommended h2 { font-size: 26px; font-weight: 700; margin-left: 32px; color: var(--green-dark); line-height: 1.1; }
      .recommended .sub { font-size: 11.5px; color: #000; margin-left: 32px; margin-top: 2px; }
      .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 27px; margin: 34px 45px; }
      .card {
          background: #f3fdee; border: 1px solid var(--green-border); border-radius: 6px;
          padding: 20px;
      }
      .card:hover {
          box-shadow: 0 6px 14px rgba(4, 65, 0, 0.79);
      }
      .card-head { display: flex; gap: 8px; align-items: center; }
      .avatar {
          width: 48px; height: 48px; border-radius: 50%; border: 1px solid #333;
          background: #fff; display: flex; align-items: center; justify-content: center;
          font-size: 24px; flex-shrink: 0;
      }
      .card-name { font-size: 14px; color: #000; line-height: 1.2; }
      .card-role, .card-phone { font-size: 11.5px; color: var(--text-muted); line-height: 1.25; }
      .card-info { margin-top: 15px; font-size: 11.5px; color: #333; }
      .card-info div { display: flex; align-items: center; gap: 6px; margin-bottom: 6px; }
      .card-info svg { flex-shrink: 0; }
      .card-foot { display: flex; align-items: center; gap: 14px; margin-top: 14px; }
      .verified {
          display: inline-flex; align-items: center; gap: 4px;
          font-size: 9.5px; color: var(--green-dark);
          border: 1px solid #1fbf5b; background: #e5fbea; border-radius: 6px;
          padding: 2px 8px 2px 4px; height: 20px;
      }
      .stars { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: #000; }
      .stars .s { color: var(--star); font-size: 13px; letter-spacing: 0; }
      .stars .s .off { color: #cfcfcf; }

      .more { display: flex; align-items: center; gap: 12px; margin: 34px 0 0 36px; }
      .more span { font-size: 16px; font-weight: 500; color: var(--green-dark); }
      .more button {
          background: var(--green); color: #fff; font-weight: 700; font-size: 17px;
          padding: 7px 25px; border-radius: 6px; border: 0.5px solid #000;
      }

      /* ---------- Wave + Register section ---------- */
      .lower { position: relative; margin-top: 100px; padding: 96px 0 0;}
      .lower::before {
          content: ''; position: absolute; left: -10%; right: -10%; top: 0; height: 300px;
          background: #298100; border-radius: 50% 50% 0 0 / 100% 100% 0 0; z-index: 0;
      }
      .lower::after {
          content: ''; position: absolute; left: -10%; right: -10%; top: 95px; bottom: 0;
          background: #298100; border-radius: 50% 50% 0 0 / 60px 60px 0 0; z-index: 0;
      }
      .lower-inner {
          position: relative; z-index: 1; display: flex; justify-content: space-between;
          align-items: flex-end; gap: 30px; padding: 0 56px; flex-wrap: wrap;
      }
      .cta { padding-bottom: 90px; max-width: 320px; }
      .cta h3 { font-size: 26px; line-height: 1.2; font-weight: 500; color: var(--green-soft); }
      .cta h3 em { font-style: normal; color: var(--green-soft); font-weight: 500; }
      .cta p { font-size: 14px; line-height: 1.25; margin-top: 8px; color: var(--green-soft); }
      .cta .contact { font-size: 14px; margin-top: 20px; }
      .socials { display: flex; gap: 6px; margin-top: 12px; }
      .socials span { width: 35px; height: 35px; display: inline-flex; }

      .register {
          width: 500px; background: #ecfde3; border: 1px solid #77c25e; border-radius: 30px;
          box-shadow: 0 6px 14px rgba(0,0,0,.18); padding: 32px 25px 24px; margin-bottom: 0;
          transform: translateY(-20px);
      }
      .register h4 { text-align: center; font-size: 20px; font-weight: 700; color: var(--green-dark); margin-bottom: 20px; }
      .register input {
          display: block; width: 100%; height: 40px; margin-bottom: 8px; padding: 0 8px;
          border: 1px solid var(--green-dark); border-radius: 6px; background: #eefee4;
          color: var(--green-dark); font-size: 12px; font-family: inherit; outline: none;
      }
      .register input::placeholder { color: var(--green-dark); }
      .register input.tight { margin-bottom: 6px; height: 40px; }
      .role { display: flex; gap: 9px; margin: 22px; }
      .role button {
          flex: 1; height: 40px; background: var(--green); color: #fff;
          border: 1px solid var(--green-dark); border-radius: 6px; font-size: 13px;
      }
      .role button.off { opacity: .95; }
      .register .submit {
          width: 100%; height: 40px; background: var(--green); color: #fff;
          border: 1px solid var(--green-dark); border-radius: 6px; font-size: 15px; margin-top: 20px;
      }
      .register .login { text-align: center; font-size: 15px; margin: 25px; color: var(--green-dark); }

      footer {
          position: relative; z-index: 1; background: #298100;
          text-align: center; font-size: 13px; color: var(--green-soft); padding: 60px 0 24px;
      }

      @media (max-width: 860px) {
          .navbar { flex-wrap: wrap; gap: 12px; padding: 16px 20px; }
          .nav-links { order: 3; width: 100%; justify-content: center; }
          .hero h1 { font-size: 28px; }
          .cards { grid-template-columns: 1fr; }
          .lower-inner { padding: 0 20px; justify-content: center; }
          .cta { padding-bottom: 20px; }
          .register { transform: none; width: 100%; max-width: 340px; }
      }
  </style>
</head>
<body>
  
  @yield('header', View::make('front.layouts.header'))
  
  @yield('main')
  
  @include('front.layouts.footer')

</body>
</html>