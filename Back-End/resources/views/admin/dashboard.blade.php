@extends('layouts.admin')

@section('content')
    <div class="background_index">
        <div class="container card-container-AuthLoginRegister mt-5 pb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header text-center bg-custom text-uppercase font-weight-bold">Ti diamo il benvenuto su BoolBnB</div>
                        <img class="card-img-top" src="https://a0.muscache.com/im/pictures/fdb46962-10c1-45fc-a228-d0b055411448.jpg?im_w=720" alt="Immagine di benvenuto">
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="create text-center center-block">
                                <h4 class="text-button">Visualizza le tue proprietà</h4>
                                <a class="btn btn-custom primary-colour btn-large" href="{{route('admin.apartments.index')}}">Cerca
                                <i class="fa-solid fa-map"></i>
                                </a>
                                <a class="btn btn-custom primary-colour btn-large" href="{{route('admin.emails.index')}}">Messaggi <i class="fa-solid fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
