@extends('layouts.admin')

@section('content')
<div class="background_index">
    <div class="container mt-4">
        @if($message != '')
        <div class="alert alert-success">
            {{ $message }}
        </div>
        @endif
        <div class="row">
            <div class="col-12 my-5 main-background-color main-box-shadow p-2">
                <div class="d-md-flex justify-content-md-between align-items-md-start">
                    <div >
                        <h1 style="margin-left: 15px;">{{ $apartment->title }}</h1>
                        <h5 style="margin-left: 15px;">{{$apartment->address}}</h5>
                    </div>
                    <div class="mt-4 mt-md-0">
                        <a href="{{route('admin.apartments.index')}}" class="btn primary-colour rounded-3 ps-3 pe-3 mt-4" style="margin-right: 15px;">Torna ai tuoi appartamenti</a>
                    </div>
                    
                </div>
               
                
            </div>
            <div class=" col-12 col-lg-6 d-flex align-items-center justify-content-center">
                @if($apartment->img)
                    <img class="img-fluid  form-box-shadow" src="{{ asset('storage/'. $apartment->img) }}" style="max-width: 100%; max-height:100% object-fit:contain;">
                @else
                    <img class="img-fluid  form-box-shadow" src="https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png" style="max-width: 100%; max-height:100% object-fit:contain;">
                @endif
            </div>
            <div class="col-12 col-lg-6 d-flex justify-content-center align-items-center">
                <div class="card my-4 main-background-color main-box-shadow" style="width: 18rem;">
                    <div class="card-header">
                      Informazioni:
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item main-background-color"><i class="fa-solid fa-door-open"></i> <strong>Stanze </strong> {{$apartment->n_rooms}}</li>
                        <li class="list-group-item main-background-color"><i class="fa-solid fa-bed"></i></i> <strong>Posti letto </strong> {{$apartment->n_beds}}</li>
                        <li class="list-group-item main-background-color"><i class="fa-solid fa-toilet-paper"></i> <strong>Bagni </strong> {{$apartment->n_bathrooms}}</li>
                        <li class="list-group-item main-background-color"><i class="fa-solid fa-sack-dollar"></i> <strong>Prezzo </strong> {{$apartment->price}} &euro;</li>
                        <li class="list-group-item main-background-color"><i class="fa-solid fa-ruler-combined"></i>  {{$apartment->mq}} mq</li>
                        <li class="list-group-item main-background-color">
                            <div>
                                @if($apartment->visible)
                                    <i class="fa-solid fa-eye"></i> <strong>Visibile </strong> <i class="fa-solid fa-check" style="color: #00ff00;"></i>
                                @else
                                    <i class="fa-solid fa-eye-slash"></i> <strong>Visibile </strong> <i class="fa-solid fa-xmark" style="color: #ff0000;"></i>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item main-background-color">  
                            @if($apartment->services->count() > 0)
                                <p class="control-label mb-3"><i class="fa-solid fa-circle-info"></i> <strong>Servizi </strong></p>
                                <ul>
                                    @foreach($apartment->services as $service)
                                        <li>{{ $service->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Nessun servizio.</p>
                            @endif
                        </li>
                        <li class="list-group-item main-background-color">
                        <?php
                            $currentTimestamp = time(); // Ottieni il timestamp UNIX corrente
                            $currentDate = strtotime('now'); // Converti il timestamp corrente in un oggetto DateTime
                            date_default_timezone_set('Europe/Rome'); // Imposta il fuso orario a Roma
                            
                            if (count($apartment->sponsorships) > 0) {
                                $isSponsored = false;
                            
                                foreach ($apartment->sponsorships as $sponsorship) {
                                    $endDate = strtotime($sponsorship->pivot->end_date); // Converti la data di fine in timestamp
                            
                                    if ($currentTimestamp < $endDate) {
                                        // L'appartamento è sponsorizzato
                                        $isSponsored = true;
                                        break; // Non c'è bisogno di continuare a controllare le altre sponsorizzazioni
                                    }
                                }
                            
                                if ($isSponsored) {
                                    echo "<p><strong>Sponsorizzato fino al</strong> <br>" . $sponsorship->pivot->end_date . "</p>";
                                } else {
                                    echo '<p>Non sponsorizzato</p>';
                                }
                            } else {
                                echo '<p">Non sponsorizzato</p>';
                            }
                            
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end align-items-center">
                        <a class="btn btn-sm btn-warning mx-2" href="{{route('admin.apartments.edit', $apartment->id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form class="form-delete delete-apartment-form" action="{{route('admin.apartments.destroy', $apartment->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button data-apartment-title="{{ $apartment->title }}" type="submit" class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <div class="card p-3 main-background-color main-box-shadow mb-5">
                <p>{{ $apartment->description }}</p>
            </div>
        </div>
    </div>
    <div class="container mb-4">
        <hr>
        <div class="d-flex justify-content-center">
            <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.59.0/maps/maps-web.min.js"></script>
                <link href='https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css' rel='stylesheet' />
        
        
                <div id="map" style="width: 100%; height: 400px;"></div>
        
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                // CONFIGURAZIONE API KEY LAT E LONG
                let apiKey = 'zXBjzKdSap3QJnfDcfFqd0Ame7xXpi1p';
                let apartmentLat = {{ $apartment->latitude }};
                let apartmentLgn = {{ $apartment->longitude }};
                // CONFIGURAZIONE MAPPA 
                let map = tt.map({
                key: apiKey,
                container: 'map',
                center: [apartmentLgn, apartmentLat],
                zoom: 13
                });
                // DATI MARKER
                let markerHeight = 50;
                let markerRadius = 10;
                let linearOffset = 25;
        
                let popupOffsets = {
                'top': [0, 0],
                'top-left': [0, 0],
                'top-right': [0, 0],
                'bottom': [0, -markerHeight],
                'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                'left': [markerRadius, (markerHeight - markerRadius) * -1],
                'right': [-markerRadius, (markerHeight - markerRadius) * -1]
                };
                // Aggiungi un gestore di eventi per il click sulla mappa
                map.on('click', function(e) {
                let popup = new tt.Popup({
                    offset: popupOffsets,
                    className: 'my-class'
                })
                .setLngLat(e.lngLat)
                .setHTML("<span>{{ $apartment->address }}</span>")
                .addTo(map);
                });
                let marker = new tt.Marker().setLngLat([apartmentLgn, apartmentLat]).addTo(map);
                });
            </script>
            </div>
        
        </div>
    </div>
</div>
@include('admin.partials.modal_delete')
@endsection