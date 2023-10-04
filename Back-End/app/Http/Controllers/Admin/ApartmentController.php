<?php

namespace App\Http\Controllers\Admin;
use App\Models\Service;
use App\Models\Apartment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $datas = $request->all();

        if(isset($datas['message'])){
            $message = $datas['message'];
        }else{
            $message = '';
        }

        if(isset($datas['messageNoAuth'])){
            $messageNoAuth = $datas['messageNoAuth'];
        }else{
            $messageNoAuth = '';
        }

        $apartments = Apartment::where('user_id', Auth::id())->get();
        return view('admin.apartments.index', compact('apartments', 'message', 'messageNoAuth'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.apartments.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApartmentRequest $request)
    {
        $form_data = $request->all();
        $form_data['user_id'] = Auth::id();

        // Effettua una chiamata API per ottenere le coordinate
        $address = urlencode($form_data['address']);
        $apiKey = config('services.tomtom.key'); // Assicurati di avere configurato la chiave di TomTom nei tuoi servizi

        $apiUrl = "https://api.tomtom.com/search/2/geocode/%7B$address%7D.json?key={$apiKey}";

        try{
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);
    
            if($data && isset($data['results'][0]['position'])){
                $coordinates = $data['results'][0]['position'];
                $form_data['latitude'] = $coordinates['lat'];
                $form_data['longitude'] = $coordinates['lon'];
            }
        }catch(\Exception $e) {
            // Gestisci eventuali errori nella chiamata API o nella decodifica JSON
            // ...
        }
    

        $apartment = new Apartment();

        if($request->hasFile('img')){
            $path = Storage::put('apartments-img', $request->img);
            $form_data['img'] = $path;
        }

        $form_data['slug'] =  $apartment->generateSlug($form_data['title']);
        $apartment->fill($form_data);

        $apartment->save();

        if($request->has('services')){
            $services = $request->input('services');
            $apartment->services()->attach($services);
        }

        $message = 'Creazione appartamneto completata';
        return redirect()->route('admin.apartments.index', ['message' => $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment, Request $request)
    {
        $message = '';

        // Verifica se l'utente autenticato è il proprietario dell'appartamento
        if (Auth::id() === $apartment->user_id) {

            $datas = $request->all();

            if(isset($datas['message'])){
                $message = $datas['message'];
            }else{
                $message = '';
            }

            return view('admin.apartments.show', compact('apartment', 'message'));
        } else {
            $messageNoAuth = 'Stai cercando di visualizzare un appartamento non tuo';
            return redirect()->route("admin.apartments.index", compact('messageNoAuth'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        $services = Service::all();
        $messageNoAuth = 'Stai cercando di modificare un appartamento non tuo';

        if(Auth::id() === $apartment->user_id){
            return view("admin.apartments.edit", compact("apartment", "services"));
        }else{
            return redirect()->route("admin.apartments.index", compact('messageNoAuth'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApartmentRequest  $request
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        $form_data = $request->all();
        
         // Effettua una chiamata API per ottenere le coordinate
         $address = urlencode($form_data['address']);
         $apiKey = config('services.tomtom.key'); // Assicurati di avere configurato la chiave di TomTom nei tuoi servizi
 
         $apiUrl = "https://api.tomtom.com/search/2/geocode/%7B$address%7D.json?key={$apiKey}";
 
         try{
             $response = file_get_contents($apiUrl);
             $data = json_decode($response, true);
     
             if($data && isset($data['results'][0]['position'])){
                 $coordinates = $data['results'][0]['position'];
                 $form_data['latitude'] = $coordinates['lat'];
                 $form_data['longitude'] = $coordinates['lon'];
             }
         }catch(\Exception $e) {
             // Gestisci eventuali errori nella chiamata API o nella decodifica JSON
             // ...
         }

        if($request->hasFile('img')){
            if($apartment->img){
                Storage::delete($apartment->img);
            }
            
            $path = Storage::put('apartments-img', $request->img);
            $form_data['img'] = $path;
        }

        if($request->has('services')) {
            $services = $request->input('services');
            $apartment->services()->sync($services);
        }else{
            $apartment->services()->detach();
        }
        
        $form_data['slug'] =  $apartment->generateSlug($form_data['title']);
        $apartment->update($form_data);
        
        $message = 'Aggiornamento appartamento completato';
        return redirect()->route('admin.apartments.show', compact('apartment', 'message'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {

        // Verifica se l'utente attualmente autenticato è il proprietario dell'appartamento
        if (Auth::id() === $apartment->user_id) {
        // Elimina l'immagine dell'appartamento se esiste
        if ($apartment->img) {
            Storage::delete($apartment->img);
        }

        // Rimuovi i servizi associati all'appartamento
        $apartment->services()->detach();

        // Elimina l'appartamento dal database
        $apartment->delete();

        $message = 'Cancellazione appartamento completata';
        return redirect()->route('admin.apartments.index', compact('message'));
        } else {
            $messageNoAuth = 'Stai cercando di cancellare un appartamento non tuo';
            return redirect()->route("admin.apartments.index", compact('messageNoAuth'));
        }
    }
}
