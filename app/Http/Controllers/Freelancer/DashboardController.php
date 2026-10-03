<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->freelancerProfile()->firstOrCreate([]);

        $fields = ['title', 'bio', 'skills', 'hourly_rate', 'phone', 'address', 'city'];
        $filled = collect($fields)->filter(fn ($f) => filled($profile->$f))->count();
        $completeness = (int) round($filled / count($fields) * 100);

        return view('freelancer.dashboard', compact('user', 'profile', 'completeness'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->freelancerProfile()->firstOrCreate([]);

        return view('freelancer.profile-edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'title'       => ['nullable', 'string', 'max:120'],
            'bio'         => ['nullable', 'string', 'max:2000'],
            'skills'      => ['nullable', 'string', 'max:500'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'address'     => ['nullable', 'string', 'max:255'],
            'city'        => ['nullable', 'string', 'max:120'],
        ]);

        $user = $request->user();
        $user->update(['name' => $data['name']]);
        unset($data['name']);

        $user->freelancerProfile()->updateOrCreate([], $data);

        return redirect()->route('freelancer.dashboard')->with('success', 'Profile updated.');
    }
}
