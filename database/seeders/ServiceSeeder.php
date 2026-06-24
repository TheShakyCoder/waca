<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['icon' => '👥', 'title' => 'Community Groups',  'description' => 'Regular social groups for all ages — from toddler mornings to senior coffee clubs. Everyone is welcome.',         'order' => 1],
            ['icon' => '🎨', 'title' => 'Arts & Crafts',      'description' => 'Weekly art classes, pottery workshops, and creative sessions for children and adults alike.',                    'order' => 2],
            ['icon' => '⚽', 'title' => 'Youth Activities',    'description' => 'After-school clubs, sports sessions, and holiday programmes to keep young people active and engaged.',           'order' => 3],
            ['icon' => '🍽️', 'title' => 'Community Café',      'description' => 'Enjoy a hot meal, fresh coffee, and friendly conversation in our welcoming on-site café.',                       'order' => 4],
            ['icon' => '🤝', 'title' => 'Support Services',    'description' => 'Access advice, foodbank referrals, and wellbeing support from our team of trained volunteers.',                  'order' => 5],
            ['icon' => '🏛️', 'title' => 'Venue Hire',          'description' => 'Affordable hall and room hire for parties, meetings, and community events throughout the year.',                'order' => 6],
        ];

        foreach ($services as $service) {
            $existing = Service::where('title', $service['title'])->first();

            // Assign a pastel, but keep an already-generated hex stable across reseeds.
            $color = $existing && str_starts_with((string) $existing->color, '#')
                ? $existing->color
                : Service::randomPastel();

            Service::updateOrCreate(['title' => $service['title']], [...$service, 'color' => $color]);
        }
    }
}
