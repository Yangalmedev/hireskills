<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfile;
use App\Models\HireRequest;
use App\Support\Abuyog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HireRequestController extends Controller
{
    /** The request form for one freelancer. */
    public function create(Request $request, FreelancerProfile $freelancer)
    {
        $freelancer->load('user');

        return view('employer.requests.create', [
            'profile'         => $freelancer,
            'barangayGroups'  => Abuyog::grouped(),
            'defaultBarangay' => $request->user()->employerProfile?->barangay,
            'budgetTypes'     => HireRequest::BUDGET_TYPES,
        ]);
    }

    public function store(Request $request, FreelancerProfile $freelancer)
    {
        $employer = $request->user();

        $alreadyPending = HireRequest::where('employer_id', $employer->id)
            ->where('freelancer_profile_id', $freelancer->id)
            ->where('status', HireRequest::PENDING)
            ->exists();

        if ($alreadyPending) {
            return redirect()->route('employer.requests.index')
                ->with('error', 'You already have a pending request with this freelancer. Wait for their reply or cancel it first.');
        }

        $data = $request->validate([
            'title'          => ['required', 'string', 'max:120'],
            'description'    => ['required', 'string', 'min:10', 'max:1000'],
            'barangay'       => ['nullable', Rule::in(Abuyog::all())],
            'address'        => ['nullable', 'string', 'max:150'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'budget'         => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'budget_type'    => ['nullable', 'required_with:budget', Rule::in(array_keys(HireRequest::BUDGET_TYPES))],
        ], [
            'description.min'        => 'Please describe the job in at least 10 characters.',
            'preferred_date.after_or_equal' => 'The preferred date cannot be in the past.',
            'budget_type.required_with' => 'Choose whether the budget is per hour, per day or per job.',
        ]);

        if (! isset($data['budget']) || $data['budget'] === '') {
            $data['budget'] = null;
            $data['budget_type'] = null;
        }

        HireRequest::create($data + [
            'freelancer_profile_id' => $freelancer->id,
            'employer_id'           => $employer->id,
            'status'                => HireRequest::PENDING,
        ]);

        return redirect()->route('employer.requests.index')
            ->with('success', 'Your request was sent. You will see the freelancer’s reply here.');
    }

    /** All requests this employer has sent. */
    public function index(Request $request)
    {
        $status = $request->query('status');
        if (! in_array($status, HireRequest::STATUSES, true)) {
            $status = null;
        }

        $base = HireRequest::where('employer_id', $request->user()->id);

        $counts = (clone $base)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $requests = (clone $base)
            ->with('freelancer.user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->take(100)
            ->get();

        return view('employer.requests.index', compact('requests', 'counts', 'status'));
    }

    /** Employer changes their mind while the request is still pending. */
    public function cancel(Request $request, HireRequest $hireRequest)
    {
        $this->owned($request, $hireRequest);

        if ($hireRequest->status !== HireRequest::PENDING) {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $hireRequest->update(['status' => HireRequest::CANCELLED]);

        return back()->with('success', 'Request cancelled.');
    }

    /** The job is done: this also unlocks reviews for that freelancer. */
    public function complete(Request $request, HireRequest $hireRequest)
    {
        $this->owned($request, $hireRequest);

        if ($hireRequest->status !== HireRequest::ACCEPTED) {
            return back()->with('error', 'Only accepted requests can be marked as completed.');
        }

        $hireRequest->update(['status' => HireRequest::COMPLETED, 'completed_at' => now()]);

        return back()->with('success', 'Marked as completed. You can now review this freelancer.');
    }

    private function owned(Request $request, HireRequest $hireRequest): void
    {
        abort_unless((int) $hireRequest->employer_id === (int) $request->user()->id, 404);
    }
}
