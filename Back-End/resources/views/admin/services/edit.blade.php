@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center">
                <div class="col-12 my-3">
                    <h1>Modifica Servizio</h1>
                </div>
                <div>
                    <a href="{{ route('admin.services.index') }}" class="btn primary-colour">Visualizza i servizi</a>
                </div>
            </div>
        </div>
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-6">
            <form action=" {{ Route('admin.services.update', $service) }} " method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group border p-4">
                        <div class="row">
                            <div class="col-12 my-3">
                                <!-- Titolo -->
                                <label class="control-label my-3">Nome</label>
                                <input type="text" name="name" id="name" placeholder="Inserisci il nome del servizio" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $service->name}}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 text-center my-5">
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-success">Aggiungi</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection