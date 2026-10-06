<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboard;
use App\Http\Controllers\Freelancer\DashboardController as FreelancerDashboard;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// ---- Public pages ----
Route::view('/', 'front.home')->name('home');
Route::view('/about', 'front.about')->name('about');

// Browsing is public; the profile page itself checks login inside the controller
Route::get('/freelancers', [FreelancerController::class, 'index'])->name('freelancers.index');
Route::get('/freelancers/{freelancer}', [FreelancerController::class, 'show'])->name('freelancers.show');

// ---- Guests only ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AccountController::class, 'showLogin'])->name('login');
    Route::post('/login', [AccountController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AccountController::class, 'showRegistration'])->name('register');
    Route::post('/register', [AccountController::class, 'register'])->name('register.store');
});

// ---- Logged in ----
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::post('/freelancers/{freelancer}/reviews', [ReviewController::class, 'store'])
        ->middleware('role:employer')
        ->name('freelancers.reviews.store');

    Route::middleware('role:freelancer')->prefix('freelancer')->name('freelancer.')->group(function () {
        Route::get('/dashboard', [FreelancerDashboard::class, 'index'])->name('dashboard');
        Route::get('/profile', [FreelancerDashboard::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [FreelancerDashboard::class, 'update'])->name('profile.update');
    });

    Route::middleware('role:employer')->prefix('employer')->name('employer.')->group(function () {
        Route::get('/dashboard', [EmployerDashboard::class, 'index'])->name('dashboard');
        Route::get('/profile', [EmployerDashboard::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [EmployerDashboard::class, 'update'])->name('profile.update');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    });
});
