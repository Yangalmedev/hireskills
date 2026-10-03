<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerProfile;
use Illuminate\Http\Request;

class FreelancerController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $city = trim((string) $request->query('city'));

        $freelancers = FreelancerProfile::with('user')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('skills', 'like', "%{$q}%")
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($city, fn ($query) => $query->where('city', 'like', "%{$city}%"))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('employer.freelancers.index', compact('freelancers', 'q', 'city'));
    }

    public function show(FreelancerProfile $freelancer)
    {
        $freelancer->load('user');

        return view('employer.freelancers.show', ['profile' => $freelancer]);
    }
}
