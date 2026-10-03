<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isFreelancer(): bool { return $this->role === 'freelancer'; }
    public function isEmployer(): bool   { return $this->role === 'employer'; }

    public function freelancerProfile(): HasOne
    {
        return $this->hasOne(FreelancerProfile::class);
    }

    public function employerProfile(): HasOne
    {
        return $this->hasOne(EmployerProfile::class);
    }

    /** Where this user lands after login. */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin'      => route('admin.dashboard'),
            'employer'   => route('employer.dashboard'),
            default      => route('freelancer.dashboard'),
        };
    }
}
