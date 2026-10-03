<?php

namespace App\Http\Controllers;

use App\Models\EmployerProfile;
use App\Models\FreelancerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function showRegistration(Request $request)
    {
        $role = in_array($request->query('role'), ['freelancer', 'employer']) ? $request->query('role') : 'freelancer';

        return view('account.registration', compact('role'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', 'in:freelancer,employer'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create($data);

        $user->isFreelancer()
            ? FreelancerProfile::create(['user_id' => $user->id])
            : EmployerProfile::create(['user_id' => $user->id]);

        Auth::login($user);
        $request->session()->regenerate();

        // Back to the freelancer they were trying to view, else their dashboard
        return redirect()->intended($user->dashboardRoute())->with('success', 'Welcome to HireSkills!');
    }

    public function showLogin()
    {
        return view('account.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(Auth::user()->dashboardRoute());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function dashboard()
    {
        return redirect(Auth::user()->dashboardRoute());
    }
}
