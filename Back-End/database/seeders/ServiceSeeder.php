<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = ['Wi-Fi Gratuito', 'Colazione Inclusa', 'Parcheggio', 'Servizio in Camera', 'Piscina e Spa', 'Lavanderia', 'Ristorante', 'Sala Riunioni', 'Noleggio Auto'];
        $icons = ['fa-solid fa-wifi','fa-solid fa-mug-saucer','fa-solid fa-square-parking','fa-solid fa-bell-concierge','fa-solid fa-water-ladder','fa-solid fa-jug-detergent','fa-solid fa-utensils','fa-solid fa-user-tie','fa-solid fa-car'];
   
        foreach ($services as $key => $serviceName) {
            $service = new Service();
            $service->name = $serviceName;
            $service->icon = $icons[$key];
            $service->save();
        }
    }

}
