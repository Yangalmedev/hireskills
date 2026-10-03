<?php

namespace Database\Seeders;

use App\Models\EmployerProfile;
use App\Models\FreelancerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(['email' => 'admin@hireskills.test'], [
            'name' => 'Admin', 'password' => 'password', 'role' => 'admin',
        ]);

        // Demo employer
        $emp = User::updateOrCreate(['email' => 'employer@hireskills.test'], [
            'name' => 'Demo Employer', 'password' => 'password', 'role' => 'employer',
        ]);
        EmployerProfile::updateOrCreate(['user_id' => $emp->id], [
            'company_name' => 'Demo Co.', 'city' => 'Cebu City', 'bio' => 'We hire local talent.',
        ]);

        // Demo freelancers
        $samples = [
            ['Maria Santos', 'Plumber', 'plumbing, pipe repair, installation', 250, 'Cebu City'],
            ['Juan dela Cruz', 'Photographer', 'events, portraits, editing', 500, 'Mandaue'],
            ['Ana Reyes', 'Web Developer', 'laravel, php, tailwind', 600, 'Cebu City'],
            ['Pedro Lim', 'Electrician', 'wiring, repairs, solar', 300, 'Lapu-Lapu'],
        ];

        foreach ($samples as $i => [$name, $title, $skills, $rate, $city]) {
            $u = User::updateOrCreate(['email' => "freelancer{$i}@hireskills.test"], [
                'name' => $name, 'password' => 'password', 'role' => 'freelancer',
            ]);
            FreelancerProfile::updateOrCreate(['user_id' => $u->id], [
                'title' => $title, 'skills' => $skills, 'hourly_rate' => $rate, 'city' => $city,
                'bio' => "Experienced {$title} available for local jobs.",
            ]);
        }
    }
}
