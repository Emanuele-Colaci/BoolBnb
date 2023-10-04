<?php

namespace App\Http\Controllers\Admin;

use Braintree\Gateway;
use App\Models\Apartment;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SponsorshipController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sponsorships = Sponsorship::all();
        // Vengono recuperati gli appartamenti associati all'utente autenticato
        $userApartments = auth()->user()->apartments;
        $userSponsors = auth()->user()->sponsors;
        $apartments = Apartment::all()->where('user_id', $user_id);

        $gateway = new Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchant_id'),
            'publicKey' => config('services.braintree.public_key'),
            'privateKey' => config('services.braintree.private_key'),
        ]);

        $token = $gateway->clientToken()->generate();

        return view('admin.sponsorships.index', compact('gateway', 'token', 'userSponsors', 'userApartments', 'sponsorships', 'user_id', 'apartments'));
    }
}
