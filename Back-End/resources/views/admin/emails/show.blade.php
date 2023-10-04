@extends('layouts.admin')

@section('content')
  
<div class="background_index">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card mt-5">
                    <div class="card-header">
                      {{$email->apartment->title}}
                    </div>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item">Da: {{$email->name}}</li>
                      <li class="list-group-item">Email: {{$email->email}}</li>
                      <li class="list-group-item">Messaggio: {{$email->content}}</li>
                    </ul>
                  </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <a class="btn btn-sm primary-colour me-1" href="{{route('admin.emails.index')}}">Torna ai messaggi</a>
                <form class="form-delete delete-apartment-form" action="{{route('admin.emails.destroy', $email->id)}}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button data-apartment-title="{{ 'l\'email di ' .$email->name }}" type="submit" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </form> 
              </div>
        </div>
    </div>
</div>

@include('admin.partials.modal_delete')
@endsection