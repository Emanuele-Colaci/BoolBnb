<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    public function index()
    {
        $sponsor = Sponsorship::all();

        return response()->json($sponsor);
    }
}
