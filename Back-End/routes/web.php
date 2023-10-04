<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\SponsorshipController;
use App\Http\Controllers\Admin\ViewController;
use App\Http\Controllers\Admin\DashboardController as DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',[DashboardController::class, 'index'])->name('dashboard');
    Route::resource('apartments', ApartmentController::class);
    Route::get('/sponsorship', [SponsorshipController::class, 'index'])->name('sponsorships.index');
    Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process_payment');
    Route::resource('emails', LeadController::class);
    Route::get('views/{apartment}', [ViewController::class, 'index'])->name('views.index');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
