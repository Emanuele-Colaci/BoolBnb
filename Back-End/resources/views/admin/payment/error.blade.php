@extends('layouts.admin')

@section('content')
    <div class="card mt-0 box-shadow">
        <div class="card-body d-flex flex-column gap-3 py-3">

            <div class="container">
                <div class="d-flex flex-column align-items-center gap-3">
                    <h1 class="text-gradient m-0">Si è verificato un errore</h1>
                    <a href="{{ route('admin.dashboard') }}"><button class="styled-btn">Torna alla Dashboard</button></a>
                </div>
            </div>

        </div>
    </div>
@endsection