<?php

namespace Database\Seeders;

use App\Models\Sponsorship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsors = [
            [
                'name' => 'Base',
                'price' => 2.99,
                'duration' => 24,
            ],
            [
                'name' => 'Premium',
                'price' => 5.99,
                'duration' => 72,
            ],
            [
                'name' => 'Gold',
                'price' => 9.99,
                'duration' => 144,
            ],
        ];

        foreach ($sponsors as $sponsor) {
            $newSponsor = new Sponsorship();
            $newSponsor->name = $sponsor['name'];
            $newSponsor->price = $sponsor['price'];
            $newSponsor->duration = $sponsor['duration'];
            $newSponsor->save();
        }
    }
}
