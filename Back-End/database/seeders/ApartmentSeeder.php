<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apartments = config('apartments');
        foreach ($apartments as $apartment){
            $new_apartment = new Apartment();
            $new_apartment->title = $apartment['title'];
            $new_apartment->slug = Apartment::generateSlug($apartment['title']);
            $new_apartment->n_rooms = $apartment['n_rooms'];
            $new_apartment->n_beds = $apartment['n_beds'];
            $new_apartment->n_bathrooms = $apartment['n_bathrooms'];
            $new_apartment->mq = $apartment['mq'];
            $new_apartment->address = $apartment['address'];
            $new_apartment->latitude = $apartment['latitude'];
            $new_apartment->longitude = $apartment['longitude'];
            $new_apartment->img = $apartment['img'];
            $new_apartment->price = $apartment['price'];
            $new_apartment->description = $apartment['description'];
            $new_apartment->visible = $apartment['visible'];
            $new_apartment->user_id = $apartment['user_id'];

            $new_apartment->save();

        }
    }
}
