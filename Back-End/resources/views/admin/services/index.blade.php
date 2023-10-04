@extends('layouts.admin')

@section('content')
    <div class="background_index">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mt-5 d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn primary-colour">Vai alla dashboard</a>
                    </div>
                    <div>
                        <a href="{{ route('admin.apartments.index') }}" class="btn primary-colour">Tutte le proprietà</a>
                    </div>
                    <div>
                        <a href="{{ route('admin.services.create') }}" class="btn primary-colour">Crea un nuovo servizio</a>
                    </div>
                </div>
                <div class="my-3">
                    <h1>Servizi</h1>
                </div>
                <div class="col-12 mt-5">
                    @if($message != '')
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr class="text-center">
                                    <td>{{ $service->id }}</td>
                                    <td>{{ $service->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-warning mx-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form class="d-inline-block mx-1 delete-apartment-form" action="{{ route('admin.services.destroy', $service->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button data-apartment-title="{{ $service->name }}" type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.modal_delete')
@endsection