<?php

namespace App\Http\Controllers;

use App\Models\FreelancerProfile;
use App\Support\Abuyog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreelancerController extends Controller
{
    /** Public: anyone can browse, search and filter. */
    public function index(Request $request)
    {
        $q        = trim((string) $request->query('q'));
        $barangay = $request->query('barangay');
        $category = $request->query('category');
        $min      = $request->query('min_rate');
        $max      = $request->query('max_rate');
        $sort     = $request->query('sort', 'newest');

        if (! in_array($category, FreelancerProfile::CATEGORIES, true)) {
            $category = null;
        }
        if (! in_array($barangay, Abuyog::all(), true)) {
            $barangay = null;
        }

        $listed = fn () => FreelancerProfile::query()->whereNotNull('title')->where('title', '!=', '');

        $query = $listed()->with('user')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('skills', 'like', "%{$q}%")
                      ->orWhere('category', 'like', "%{$q}%")
                      ->orWhere('barangay', 'like', "%{$q}%")
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($category, fn ($query) => $query->where('category', $category))
            ->when($barangay, fn ($query) => $query->where('barangay', $barangay))
            ->when(is_numeric($min), fn ($query) => $query->where('hourly_rate', '>=', $min))
            ->when(is_numeric($max), fn ($query) => $query->where('hourly_rate', '<=', $max));

        match ($sort) {
            'rate_asc'  => $query->orderBy('hourly_rate'),
            'rate_desc' => $query->orderByDesc('hourly_rate'),
            default     => $query->latest(),
        };

        $freelancers = $query->paginate(9)->withQueryString();

        $categoryCounts = $listed()->selectRaw('category, count(*) as total')
            ->groupBy('category')->pluck('total', 'category');

        return view('freelancers.index', [
            'freelancers'    => $freelancers,
            'categories'     => FreelancerProfile::CATEGORIES,
            'categoryCounts' => $categoryCounts,
            'barangayGroups' => Abuyog::grouped(),
            'filters'        => compact('q', 'barangay', 'category', 'min', 'max', 'sort'),
        ]);
    }

    /** Members only: guests are sent to log in, then returned here. */
    public function show(FreelancerProfile $freelancer)
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'))
                ->with('info', 'Please log in or sign up to view freelancer profiles.');
        }

        $freelancer->load('user');

        return view('freelancers.show', ['profile' => $freelancer]);
    }
}
