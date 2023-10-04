@extends('layouts.admin')

@section('content')
@vite(['resources/js/address.js'])
<div class="background_index">
    <div class="container">
        <div class="row">
            <div class="col-12 my-3">
                <div class="d-flex justify-content-between align-items-center main-background-color main-box-shadow p-2">
                    <h2 style="margin-left: 15px;">Inserisci un nuovo appartamento</h2>
                    <a href=" {{ route('admin.apartments.index')}} " class="btn primary-colour rounded-3 ps-3 pe-3" style="margin-right: 15px;">Tutte le proprietà</a>
                </div>
                <div>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="apartment-form" action="{{ route('admin.apartments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card p-3 my-5 main-background-color main-box-shadow">
                            <div class="row">
                                <div class="class-group col-12 col-md-6">
                                    <label class="control-label mt-2">Nome dell'appartamento *</label>
                                    <input type="text" id="title" name="title" class="form-control @error('title')is-invalid @enderror form-box-shadow" placeholder="Inserisci il nome dell'appartamento" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="class-group col-12 col-md-6">
                                    <label class="control-label mt-2">Numero stanze *</label>
                                    <input type="number" id="n_rooms" name="n_rooms" class="form-control @error('n_rooms')is-invalid @enderror form-box-shadow" placeholder="Inserire il numero delle stanze" value="{{ old('n_rooms') }}" required>
                                    @error('n_rooms')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="class-group col-12 col-md-6">
                                    <label class="control-label mt-2">Posti letto *</label>
                                    <input type="number" id="n_beds" name="n_beds" class="form-control @error('n_beds')is-invalid @enderror form-box-shadow" placeholder="Inserisci posti letto" value="{{ old('n_beds') }}" required>
                                    @error('n_beds')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="class-group col-12 col-md-6">
                                    <label class="control-label mt-2">Numero bagni *</label>
                                    <input type="number" id="n_bathrooms" name="n_bathrooms" class="form-control @error('n_bathrooms')is-invalid @enderror form-box-shadow" placeholder="Inserisci il numero dei bagni" value="{{ old('n_bathrooms') }}" required>
                                    @error('n_bathrooms')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="class-group col-12 col-md-6">
                                    <label class="control-label">m<sup>2</sup> *</label>
                                    <input type="number" id="mq" name="mq" class="form-control @error('mq')is-invalid @enderror form-box-shadow" placeholder="Inserire i mq" value="{{ old('mq') }}" required>
                                    @error('mq')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="class-group col-12 col-md-6">
                                    <label class="control-label mt-2">Indirizzo *</label>
                                    <input type="text" id="address" name="address" class="form-control @error('address')is-invalid @enderror form-box-shadow" placeholder="Inserisci l'indirizzo" value="{{ old('address') }}" required>
                                    <ul id="autocomplete-list" class="list-group box-list"></ul>
                                    @error('address')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="form-group col-12 col-md-6">
                                        <label class="control-label mt-2">Prezzo</label>
                                        <input type="text" name="price" id="price" placeholder="Inserisci il prezzo" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror form-box-shadow">
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-12 col-lg-6">
                                    <label class="form-control-label mt-2">Descrizione</label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control form-box-shadow">{{ old('description') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <!-- Immagine -->
                                    <label class="control-label mt-2">Immagine</label>
                                    <input type="file" name="img" id="img" placeholder="Inserisci un'immagine rappresentativa dell'appartamento" class="form-control @error('img') is-invalid @enderror form-box-shadow" value="{{ old('img') }}">
                                    @error('img')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                
                                    <label class="control-label mt-2">Servizi: *</label>
                                    <div class="d-flex flex-wrap">
                                        @foreach($services as $service)
                                            <div class="form-check ms-2">
                                                <input type="checkbox" name="services[]" value="{{ $service->id }}" class="form-check-input @error('services') is-invalid @enderror">
                                                <label class="form-check-label">{{ $service->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-danger" id="servicesError"></div>
                                    @error('services')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-sm-6">
                                    <p class="mt-3 ms-0">Visibile</p>
                                    <div class="d-flex">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" value="1" name="visible" id="visible" checked>
                                            <label class="form-check-label" for="visible">Si</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="0" name="visible" id="visible">
                                            <label class="form-check-label" for="visible">No</label>
                                        </div>
                                    </div>
                                </div>
                                <span class="mt-3" style="font-size: 12px;">* I campi sono obbligatori</span>
                            </div>
                        </div>
                        <div class="class-group my-3">
                            <button type="submit" class="btn primary-colour rounded-3 ps-3 pe-3">Aggiungi appartamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection