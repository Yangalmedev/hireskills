<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'freelancers' => User::where('role', 'freelancer')->count(),
            'employers'   => User::where('role', 'employer')->count(),
            'total'       => User::count(),
        ];
        $users = User::where('role', '!=', 'admin')->latest()->paginate(15);

        return view('admin.dashboard', compact('stats', 'users'));
    }
}
