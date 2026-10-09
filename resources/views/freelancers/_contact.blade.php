{{--
    Clickable contact links.
    $profile : FreelancerProfile or EmployerProfile
    $mode    : 'owner'  -> shows empty tiles that link to Edit Profile (freelancer / employer dashboard)
               'public' -> shows only the channels that are linked (profile page for members)
--}}
@php
    $channels = [
        'messenger' => ['label' => 'Messenger', 'url' => $profile->messenger_url,
                        'text'  => $profile->messenger ? 'm.me/'.$profile->messenger : null,
                        'hint'  => 'Link your Messenger', 'external' => true],
        'gmail'     => ['label' => 'Gmail', 'url' => $profile->gmail_url,
                        'text'  => $profile->gmail,
                        'hint'  => 'Link your Gmail', 'external' => true],
        'phone'     => ['label' => 'Phone', 'url' => $profile->phone_url,
                        'text'  => $profile->phone,
                        'hint'  => 'Add your phone number', 'external' => false],
    ];
    $mode = $mode ?? 'public';
    // where the "+ Link ..." tiles send the owner (freelancer or employer)
    $editUrl = auth()->check() && auth()->user()->isEmployer()
        ? route('employer.profile.edit')
        : route('freelancer.profile.edit');
    $any = collect($channels)->contains(fn ($c) => $c['url']);
@endphp

<style>
    .ctiles{display:grid;gap:12px;grid-template-columns:repeat(auto-fit,minmax(210px,1fr))}
    .ctile{display:flex;align-items:center;gap:12px;padding:14px 16px;border-radius:12px;text-decoration:none;color:#fff;border:1px solid transparent;transition:transform .12s,box-shadow .12s}
    .ctile:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.14)}
    .ctile svg{width:26px;height:26px;fill:currentColor;flex-shrink:0}
    .ctile .cl{font-weight:700;font-size:15px;line-height:1.2}
    .ctile .cv{font-size:13px;opacity:.92;word-break:break-all;line-height:1.3}
    .ctile .go{margin-left:auto;font-size:18px;opacity:.9}
    .ctile.messenger{background:#0084ff}
    .ctile.gmail{background:#ea4335}
    .ctile.phone{background:var(--g2, #16a116)}
    .ctile.empty{background:#fff;color:#567;border:1px dashed #b7cdb2}
    .ctile.empty:hover{box-shadow:none;border-color:var(--g2, #16a116);color:var(--g, #0b7a0b)}
</style>

@if ($any || $mode === 'owner')
    <div class="ctiles">
        @foreach ($channels as $key => $c)
            @if ($c['url'])
                <a class="ctile {{ $key }}" href="{{ $c['url'] }}"
                   @if ($c['external']) target="_blank" rel="noopener noreferrer" @endif
                   title="{{ $key === 'phone' ? 'Call' : 'Open' }} {{ $c['label'] }}">
                    @if ($key === 'messenger')
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
                    @elseif ($key === 'gmail')
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    @else
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15 15 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.6 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.6 3.6a1 1 0 0 1-.25 1z"/></svg>
                    @endif
                    <div>
                        <div class="cl">{{ $c['label'] }}</div>
                        <div class="cv">{{ $c['text'] }}</div>
                    </div>
                    <span class="go">{{ $c['external'] ? '↗' : '☎' }}</span>
                </a>
            @elseif ($mode === 'owner')
                <a class="ctile empty" href="{{ $editUrl }}#contact-links">
                    <div>
                        <div class="cl">+ {{ $c['hint'] }}</div>
                        <div class="cv">Not linked yet</div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
@else
    <p class="muted" style="margin:0">{{ $emptyText ?? 'This freelancer hasn’t added contact links yet.' }}</p>
@endif
