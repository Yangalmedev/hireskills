<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfile;
use App\Support\Abuyog;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->employerProfile()->firstOrCreate([]);
        $freelancerCount = FreelancerProfile::count();
        $latest = FreelancerProfile::with('user')->latest()->take(4)->get();

        return view('employer.dashboard', compact('user', 'profile', 'freelancerCount', 'latest'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->employerProfile()->firstOrCreate([]);

        return view('employer.profile-edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        // tidy the phone number first (spaces/dashes, +63 -> 09)
        $request->merge(['phone' => PhoneNumber::normalize($request->input('phone'))]);

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:2000'],
            'phone'       => ['nullable', 'regex:'.PhoneNumber::PATTERN],
            'address'      => ['nullable', 'string', 'max:255'],
            'barangay'     => ['nullable', Rule::in(Abuyog::all())],
        ], [
            'phone.regex' => PhoneNumber::MESSAGE,
        ]);

        $user = $request->user();
        $user->update(['name' => $data['name']]);
        unset($data['name']);

        $user->employerProfile()->updateOrCreate([], $data);

        return redirect()->route('employer.dashboard')->with('success', 'Profile updated.');
    }
}
