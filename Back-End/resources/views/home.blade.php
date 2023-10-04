@extends('layouts.app')
@section('content')
    <div class="background_index">
        <div class="container text-center my-5">
            <div class="jumbotron primary-colour-home text-white rounded-lg p-5 rounded shadow-lg">
                <h1 class="display-4">Mettilo in evidenza!</h1>
                <p class="lead">Hai un appartamento che vuoi condividere con gli altri? Mettilo sul nostro sito!</p>
                <a href="{{ route('admin.apartments.create') }}" class="btn btn-light btn-lg">Aggiungi il tuo appartamento</a>
            </div>
        </div>

        <div class="container">
            <div class="row main-background-color main-box-shadow p-3">
                <div class="col-md-6">
                    <h2>Come Funziona</h2>
                    <p>
                        Condividi il tuo appartamento con altre persone in modo semplice e sicuro. Basta seguire questi passaggi:
                    </p>
                    <ol>
                        <li>Registra un account o effettua l'accesso.</li>
                        <li>Clicca su "Aggiungi il tuo appartamento".</li>
                        <li>Compila il modulo con i dettagli del tuo appartamento.</li>
                        <li>Carica le immagini e scegli un prezzo.</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h2>Vantaggi</h2>
                    <ul>
                        <li>Raggiungi una vasta audience di potenziali ospiti.</li>
                        <li>Offri un'esperienza unica ai tuoi ospiti.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection