<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use App\Models\View;
use App\Models\Apartment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $apartment)
    {
        // Recupera l'utente autenticato
        $user = Auth::user();
        
        // Recupera gli appartamenti associati all'utente autenticato (relazione uno-a-molti)
        $userApartments = $user->apartments;
        
        // Inizializza un array per memorizzare gli ID degli appartamenti dell'utente
        $userApartmentIds = $userApartments->pluck('id')->toArray();
        
        // Ottieni l'appartamento specifico dell'utente
        $apartment = Apartment::where('id', $apartment)
            ->whereIn('id', $userApartmentIds)
            ->first();
        
        if (!$apartment) {
            // L'appartamento specificato non appartiene all'utente, restituisci un errore
            return abort(403); // Puoi scegliere un codice di stato HTTP appropriato
        }
        
        // Calcola la data di 7 giorni fa
        $sevenDaysAgo = Carbon::now()->subDays(7);
        
        // Ottieni le visualizzazioni per l'appartamento specifico negli ultimi 7 giorni
        $apartmentViews = View::where('apartment_id', $apartment->id)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->get();
        
        // Raggruppa le visualizzazioni per data (giorno) e conta quante ci sono per ogni giorno
        $viewsByDay = $apartmentViews->groupBy(function ($view) {
            return $view->created_at->format('Y-m-d'); // Raggruppa per data (giorno)
        });
        
        // Restituisci la vista con i dati
        return view('admin.views.index', compact('viewsByDay', 'userApartments'));
    }
    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function show(View $view, Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function edit(View $view)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, View $view)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function destroy(View $view)
    {
        //
    }
}
