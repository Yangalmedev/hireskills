<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfile;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:2000'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'address'      => ['nullable', 'string', 'max:255'],
            'city'         => ['nullable', 'string', 'max:120'],
        ]);

        $user = $request->user();
        $user->update(['name' => $data['name']]);
        unset($data['name']);

        $user->employerProfile()->updateOrCreate([], $data);

        return redirect()->route('employer.dashboard')->with('success', 'Profile updated.');
    }
}
