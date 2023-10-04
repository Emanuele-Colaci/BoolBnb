<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\service;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index(){
        $apartments = Apartment::inRandomOrder()->with('services','sponsorships')->whereHas('sponsorships')->get();
        return response()->json([
            'success' => true,
            'results'  => $apartments
        ]);
    }

    public function show($slug)
    {

        $apartment = Apartment::with('services')->where('slug', $slug)->first();

        if($apartment) {

            return response()->json([
                'success' => true,
                'apartment' => $apartment
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => "Non c'è nessun appartamento"
            ])->setStatusCode(404);
        }
    }

    public function search(Request $request){
        $n_rooms = $request->input('n_rooms');
        $n_beds = $request->input('n_beds');
        $services = $request->input('services');
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $range = $request->input('range');
    
        $apartmentsFilter = Apartment::query()->with('services', 'sponsorships');
    
        if ($n_rooms !== null) {
            $apartmentsFilter->where('n_rooms', '>=', $n_rooms);
        }
    
        if ($n_beds !== null) {
            $apartmentsFilter->where('n_beds', '>=', $n_beds);
        }
    
       
    if (!empty($services)) {
        $serviceIds = explode(',', $services);
        
        foreach ($serviceIds as $id) {
            $apartmentsFilter->whereHas('services', function ($q) use ($id) {
                $q->where('services.id', $id);
            });
        }
    }
    
        if ($lat !== null && $lon !== null && $range !== null) {
            // Calcolo della distanza e ordinamento per la distanza
            $apartmentsFilter->selectRaw('*,
                (6371000 * 2 * ASIN(SQRT(POW(SIN((? - ABS(latitude)) * PI() / 180 / 2), 2) +
                COS(? * PI() / 180) * COS(ABS(latitude) * PI() / 180) * POW(SIN((? - longitude) * PI() / 180 / 2), 2)))) AS distance',
                [$lat, $lat, $lon]
            )->having('distance', '<', $range * 1000)->orderBy('distance');
        }
    
        $results = $apartmentsFilter->get();
    
        return response()->json([
            'success' => true,
            'results'  => $results
        ]);
    }
    // public function searchApartments(Request $request) {
        
    //     $apartments = Apartment::all();


    // }
    public function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
