<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stats = [
            ['label' => 'Members',        'value' => '1,248', 'icon' => '👥', 'order' => 1],
            ['label' => 'Volunteers',     'value' => '152',   'icon' => '🤝', 'order' => 2],
            ['label' => 'Years Serving',  'value' => '30+',   'icon' => '🎉', 'order' => 3],
            ['label' => 'Events a Year',  'value' => '200',   'icon' => '📅', 'order' => 4],
        ];

        foreach ($stats as $stat) {
            Stat::firstOrCreate(['label' => $stat['label']], $stat);
        }
    }
}
