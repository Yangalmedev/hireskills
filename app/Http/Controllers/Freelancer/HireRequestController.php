<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\HireRequest;
use Illuminate\Http\Request;

class HireRequestController extends Controller
{
    /** Requests that employers sent to this freelancer. */
    public function index(Request $request)
    {
        $profile = $request->user()->freelancerProfile()->firstOrCreate([]);

        $status = $request->query('status');
        if (! in_array($status, HireRequest::STATUSES, true)) {
            $status = null;
        }

        $base = HireRequest::where('freelancer_profile_id', $profile->id);

        $counts = (clone $base)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $requests = (clone $base)
            ->with('employer.employerProfile')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")   // pending first
            ->latest()
            ->take(100)
            ->get();

        return view('freelancer.requests.index', compact('requests', 'counts', 'status'));
    }

    public function accept(Request $request, HireRequest $hireRequest)
    {
        return $this->respond($request, $hireRequest, HireRequest::ACCEPTED, 'Request accepted. The employer can now see your contact links.');
    }

    public function decline(Request $request, HireRequest $hireRequest)
    {
        return $this->respond($request, $hireRequest, HireRequest::DECLINED, 'Request declined.');
    }

    private function respond(Request $request, HireRequest $hireRequest, string $newStatus, string $message)
    {
        $profile = $request->user()->freelancerProfile()->firstOrCreate([]);

        // a freelancer can only answer requests sent to them
        abort_unless((int) $hireRequest->freelancer_profile_id === (int) $profile->id, 404);

        if ($hireRequest->status !== HireRequest::PENDING) {
            return back()->with('error', 'This request was already answered or cancelled.');
        }

        $data = $request->validate([
            'reply' => ['nullable', 'string', 'max:500'],
        ]);

        $hireRequest->update([
            'status'           => $newStatus,
            'freelancer_reply' => $data['reply'] ?? null,
            'responded_at'     => now(),
        ]);

        return back()->with('success', $message);
    }
}
