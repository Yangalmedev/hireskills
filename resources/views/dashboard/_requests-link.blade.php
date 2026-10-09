@php
    $u = auth()->user();
    $active = $active ?? false;
    if ($u->isFreelancer()) {
        $pendingCount = \App\Models\HireRequest::where('freelancer_profile_id', $u->freelancerProfile?->id)
            ->where('status', 'pending')->count();
        $href = route('freelancer.requests.index'); $label = 'Hire Requests';
    } else {
        $pendingCount = 0;
        $href = route('employer.requests.index'); $label = 'My Requests';
    }
@endphp
<a @if($active) class="active" @endif href="{{ $href }}">{{ $label }}@if($pendingCount > 0) <span style="background:#c0392b;color:#fff;border-radius:10px;padding:1px 7px;font-size:12px">{{ $pendingCount }}</span>@endif</a>
