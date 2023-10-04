<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ApartmentController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\SponsorshipController;
use App\Http\Controllers\Api\ViewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apartments', [ApartmentController::class, 'index']);
Route::get('/apartment/{slug}', [ApartmentController::class, 'show']);
Route::get('/apartmentsFilter', [ApartmentController::class, 'search']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('sponsors', [SponsorshipController::class, 'index'])->name('api.sponsors.index');
Route::post('/mail', [LeadController::class, 'store']);
Route::post('/view', [ViewController::class, 'store']);



