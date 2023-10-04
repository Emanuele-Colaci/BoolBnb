@extends('layouts.admin')

@section('content')
<div class="background_index">    
    <div class="card mt-0 box-shadow main-background-color">
        <div class="card-body d-flex flex-column gap-3 py-3" style="background-color: rgba(255, 255, 255, 0)">

            {{-- Header --}}
            <div class="container main-background-color main-box-shadow p-3">
                <div class="d-inline-block text-gradient">
                    <h1>Sponsorizza Appartamenti</h1>
                </div>
                <hr class="m-0">
            </div>

            @if (count($apartments))
                <div class="container main-background-color main-box-shadow p-3">
                    <p>Ampliate la visibilità del vostro appartamento con una sponsorizzazione adattabile alle vostre esigenze! Mettiamo a vostra disposizione un'ampia gamma di opzioni di sponsorizzazione, che includono sia pacchetti standard che soluzioni personalizzate. Avete la libertà di selezionare la modalità che meglio si adatta alle vostre esigenze per promuovere il vostro appartamento e catturare l'attenzione del più ampio pubblico possibile!
                    </p>
                </div>

                <div class="container main-background-color form-box-shadow p-3"style="background-color: rgba(255, 255, 255, 0)">
                    <form id="payment-form" action="{{ route('admin.process_payment') }}" method="post"
                        class="col-12 col-lg-6 mx-auto main-background-color form-box-shadow">
                        @csrf
                        <div class="card mb-3 main-background-color">
                            <div class="card-body main-background-color form-box-shadow">
                                <h2 class="card-title">Acquista una sponsorizzazione</h2>
                                <div class="form-group">
                                    <label for="sponsorship_id">Seleziona un pacchetto:</label><br>
                                    @foreach ($sponsorships as $sponsorship)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sponsorship_id"
                                                id="sponsorship_{{ $sponsorship->id }}" value="{{ $sponsorship->id }}" required>
                                            <label class="form-check-label" for="sponsorship{{ $sponsorship->id }}">
                                                {{ $sponsorship->name }} (Prezzo: {{ $sponsorship->price }}€, Durata:
                                                {{ $sponsorship->duration }}
                                                ore)
                                            </label>
                                        </div>
                                    @endforeach

                                    {{-- Errore --}}
                                    @error('sponsorship_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mt-3">
                                    <label for="apartment_id">Scegli un appartamento:</label>
                                    <select name="apartment_id" id="apartment_id" class="form-control" required>
                                        <option value="">Seleziona un appartamento</option>
                                        @foreach ($apartments as $apartment)
                                            <option value="{{ $apartment->id }}">
                                                {{ $apartment->title }}
                                            </option>
                                        @endforeach
                                    </select>

                                    {{-- Errore --}}
                                    @error('apartment_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="dropin-wrapper" class="mt-3">
                                    <div id="checkout-message"></div>
                                    <div id="dropin-container"></div>
                                    <input id="nonce" name="payment_method_nonce" type="hidden" required />
                                    <button id="submit-button" class="btn btn-primary btn-block">Acquista</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>
                <script>
                    let button = document.getElementById('submit-button');
                    let form = document.querySelector('#payment-form');
                    let client_token = "{{ $token }}";
                    let paymentInstance;

                    braintree.dropin.create({
                        authorization: client_token,
                        container: '#dropin-container', 
                        locale: 'it_IT',
                    }, function(createErr, instance) {
                        if (createErr) {
                            console.error(createErr);
                            return;
                        }

                        form.addEventListener('submit', function(event) {
                            event.preventDefault();

                            instance.requestPaymentMethod(function(err, payload) {
                                if (err) {
                                    console.error(err);
                                    return;
                                }

                                console.log(payload.nonce)
                                document.querySelector('#nonce').value = payload.nonce;
                                form.submit();
                            });
                        });

                        //paymentInstance = instance;
                    });
                </script>
            @else
                <div class="container">
                    <h2>Non disponi di alcun appartamento</h2>
                    <button class="style-btn">Aggiungi</button>
                </div>
            @endif
        </div>
    </div>
</div>    
@endsection

<style>
    .button {
        cursor: pointer;
        font-weight: 500;
        left: 3px;
        line-height: inherit;
        position: relative;
        text-decoration: none;
        text-align: center;
        border-style: solid;
        border-width: 1px;
        border-radius: 3px;
        -webkit-appearance: none;
        -moz-appearance: none;
        display: inline-block;
    }

    .button--small {
        padding: 10px 20px;
        font-size: 0.875rem;
    }

    .button--green {
        outline: none;
        background-color: #64d18a;
        border-color: #64d18a;
        color: white;
        transition: all 200ms ease;
    }

    .button--green:hover {
        background-color: #8bdda8;
        color: white;
    }
</style>