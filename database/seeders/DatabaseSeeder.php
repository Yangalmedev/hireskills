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
        User::updateOrCreate(['email' => 'admin@hireskills.test'], [
            'name' => 'Admin', 'password' => 'password', 'role' => 'admin',
        ]);

        $emp = User::updateOrCreate(['email' => 'employer@hireskills.test'], [
            'name' => 'Demo Employer', 'password' => 'password', 'role' => 'employer',
        ]);
        EmployerProfile::updateOrCreate(['user_id' => $emp->id], [
            'company_name' => 'Demo Store', 'barangay' => 'Bito', 'city' => null,
            'address' => 'Purok 1', 'bio' => 'We hire local talent in Abuyog.',
        ]);

        // name, title, category, skills, rate, barangay
        $samples = [
            ['Maria Santos',    'Plumber',               'Home and Repair',        'plumbing, pipe repair, installation',   250, 'Bito'],
            ['Juan dela Cruz',  'Photographer',          'Events',                 'events, portraits, editing',            500, 'Santa Fe'],
            ['Ana Reyes',       'Web Developer',         'Technology',             'laravel, php, tailwind',                600, 'Nalibunan'],
            ['Pedro Lim',       'Electrician',           'Home and Repair',        'wiring, repairs, solar',                300, 'Can-uguib'],
            ['Carla Mendoza',   'Graphic Designer',      'Design and Education',   'logos, tarpaulins, branding',           450, 'Victory'],
            ['Rico Alvarez',    'Math Tutor',            'Design and Education',   'algebra, calculus, review classes',     350, 'Santo Niño'],
            ['Liza Gomez',      'Event Coordinator',     'Events',                 'planning, styling, fiesta events',      700, 'Guintagbucan'],
            ['Mark Tan',        'Barber',                'Personal Services',      'haircuts, grooming, home service',      200, 'Loyonsawang'],
            ['Joy Villanueva',  'Massage Therapist',     'Personal Services',      'hilot, massage, home service',          400, 'Buntay'],
            ['Noel Garcia',     'Habal-habal Driver',    'Transportation',         'motorcycle, passenger, delivery',       150, 'Santa Lucia'],
            ['Ben Ramos',       'Mason',                 'Construction',           'masonry, tiling, concrete work',        320, 'Pagsang-an'],
            ['Grace Uy',        'Phone & Laptop Repair', 'Technology',             'phone repair, laptop repair, software', 250, 'Tinocolan'],
            ['Ruel Cabrera',    'Fisherman / Boat Operator', 'Agriculture and Fishing', 'fishing, boat operation, fish supply', 300, 'Tabigue'],
            ['Nena Bacalso',    'Farm Hand',             'Agriculture and Fishing', 'rice, coconut, planting, harvesting',   180, 'San Isidro'],
            ['Tito Mercado',    'Carpenter',             'Construction',           'framing, roofing, furniture repair',    330, 'Burubud-an'],
        ];

        foreach ($samples as $i => [$name, $title, $category, $skills, $rate, $barangay]) {
            $u = User::updateOrCreate(['email' => "freelancer{$i}@hireskills.test"], [
                'name' => $name, 'password' => 'password', 'role' => 'freelancer',
            ]);
            FreelancerProfile::updateOrCreate(['user_id' => $u->id], [
                'title' => $title, 'category' => $category, 'skills' => $skills,
                'hourly_rate' => $rate, 'barangay' => $barangay, 'city' => null,
                'address' => 'Purok '.rand(1, 7),
                'phone' => '09'.rand(100000000, 999999999),
                'bio' => "Experienced {$title} based in Brgy. {$barangay}, Abuyog, Leyte. Reliable, on time and fairly priced.",
            ]);
        }
    }
}
