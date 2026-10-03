<div class="fcard">
    <div class="ftop">
        <div class="favatar">{{ strtoupper(substr($f->user->name, 0, 1)) }}</div>
        <div>
            <strong>{{ auth()->check() ? $f->user->name : $f->public_name }}</strong><br>
            <span class="fmuted">{{ $f->title ?: 'Freelancer' }}</span>
        </div>
    </div>
    @if($f->category)<span class="fcat">{{ $f->category }}</span>@endif
    <p class="fmuted" style="margin:10px 0">
        📍 {{ $f->barangay ? 'Brgy. '.$f->barangay : 'Abuyog, Leyte' }} &nbsp;·&nbsp;
        {{ $f->hourly_rate ? '₱'.number_format($f->hourly_rate, 0).'/hr' : 'Rate not set' }}
    </p>
    <div class="fskills">
        @foreach(array_slice($f->skills_list, 0, 3) as $s)<span class="ftag">{{ $s }}</span>@endforeach
    </div>
    <a class="btn solid fbtn" href="{{ route('freelancers.show', $f) }}">
        {{ auth()->check() ? 'View profile' : '🔒 Log in to view' }}
    </a>
</div>
