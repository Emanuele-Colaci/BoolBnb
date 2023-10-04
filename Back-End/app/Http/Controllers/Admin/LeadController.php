<?php

namespace App\Http\Controllers\Admin;


use App\Models\Lead;
use App\Models\Apartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request){

        $datas = $request->all();
    
        if(isset($datas['message'])){
            $message = $datas['message'];
        } else {
            $message = '';
        }
    
        if(isset($datas['messageNoAuth'])){
            $messageNoAuth = $datas['messageNoAuth'];
        } else {
            $messageNoAuth = '';
        }
    
        // Ottieni l'ID dell'utente autenticato
        $user_id = Auth::user()->id;
    
        // Recupera gli appartamenti associati all'utente autenticato
        $userApartments = Apartment::where('user_id', $user_id)->get();
    
        // Inizializza un array per memorizzare gli ID degli appartamenti dell'utente
        $userApartmentIds = $userApartments->pluck('id')->toArray();
    
        // Recupera i messaggi associati agli appartamenti dell'utente
        $emails = Lead::whereIn('apartment_id', $userApartmentIds)->orderBy('created_at', 'desc')->get();
    
        // Restituisci la vista con i dati
        return view('admin.emails.index', compact('emails', 'message', 'messageNoAuth', 'userApartments'));
    }

    public function show(Lead $email, Request $request)
    {
        $message = '';

        // Verifica se l'utente autenticato è il proprietario dell'appartamento
        if (Auth::id() === $email->apartment->user_id) {

            $datas = $request->all();

            if(isset($datas['message'])){
                $message = $datas['message'];
            }else{
                $message = '';
            }

            return view('admin.emails.show', compact('email', 'message'));
        } else {
            $messageNoAuth = 'Stai cercando di visualizzare messaggi non tuoi';
            return redirect()->route("admin.emails.index", compact('messageNoAuth'));
        }
    }
    public function destroy(Lead $email)
        {
            // Verifica se l'utente autenticato è il proprietario dell'appartamento associato alla email
            if (Auth::id() === $email->apartment->user_id) {
                // Elimina la email
                $email->delete();

                // Messaggio di conferma dell'eliminazione
                $message = 'Email eliminata con successo.';
            } else {
                // Messaggio di errore se l'utente non è autorizzato
                $message = 'Non sei autorizzato a eliminare questa email.';
            }

            // Reindirizza all'indice delle email con il messaggio
            return redirect()->route('admin.emails.index')->with('message', $message);
        }

}
