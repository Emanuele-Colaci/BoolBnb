@extends('layouts.admin')

@section('content')
@vite(['resources/js/isSponsored.js'])

<div class="background_index">
  <div class="container">
    <div class="col-12 col-sm-6 col-md-3 py-5 w-100">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
          <a href="{{route('admin.dashboard')}}" class="btn primary-colour me-md-2 mb-2 mb-md-0 ">Torna alla dashboard</a>
          <a href="{{route('admin.apartments.index')}}" class="btn primary-colour me-md-2 mb-2 mb-md-0 ">Torna ai tuoi appartamenti</a>
      </div>
    </div>
  </div>
      
  <div class="container">
    @if($message != '')
      <div class="alert alert-success">
          {{ $message }}
      </div>
    @endif
    @if($messageNoAuth != '')
      <div class="alert alert-danger">
          {{ $messageNoAuth }}
      </div>
    @endif
    @if($emails->isEmpty())
    <div class="d-flex justify-content-center">
      <h1 class="my-5">Non ci sono messaggi</h1>
    </div>
    @else
    <div>
      <h3>I tuoi messaggi</h3>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th></th>
          <th>Appartamento</th>
          <th>Nome</th>
          <th class="text-center display-none">Email</th>
          <th class="text-center display-none">Messaggio</th>
          <th class="text-center">Azioni</th>
        </tr>
      </thead>
      <tbody>
        @foreach($emails as $email)
        <tr>
          <td>
            @if($email->apartment->img)
              <div class="card-img d-none d-md-block  my_card shadow-lg"
                style="background-image:url('{{ asset('storage/'.$email->apartment->img) }}'); background-size: cover; background-position: center;">
              </div>
            @else
              <div class="card-img my_card d-none d-md-block"
                style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center;">
              </div>
            @endif
          </td>
          <td>{{$email->apartment->title}}</td>
          <td>
            {{$email->name}}
          </td>
          <td class="display-none">
            {{$email->email}}
          </td>
          <td class="truncate-text display-none">
            <div class="">
              {{$email->content}}
            </div>
          </td>
          <td>
            <div class="d-flex justify-content-center">
              <a class="btn btn-sm btn-primary me-1" href="{{route('admin.emails.show', $email->id)}}"><i class="fa-regular fa-envelope"></i></a>
              <form class="form-delete delete-apartment-form" action="{{route('admin.emails.destroy', $email->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button data-apartment-title="{{ 'l\'email di ' .$email->name }}" type="submit" class="btn btn-sm btn-danger">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </form> 
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>
</div>
@include('admin.partials.modal_delete')
@endsection
