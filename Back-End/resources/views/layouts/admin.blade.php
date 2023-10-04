<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BoolBnB') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">


        <header id="myHeader" class="p-0 navbar navbar-expand-md fixed-top z-index">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand d-flex align-items-center p-0" href="{{ url('/') }}">
                    <div class="logo">
                        <img class="logo-img img-fluid" src="/images/LogoNoSfondo3.png" alt="boolbnb"> <span class="letter-spacing">BoolBnB</span>
                    </div>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                        {{--<li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.stats.index') }}" class="nav-link">{{ __('Stats') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.apartments.index') }}" class="nav-link">{{ __('Apartments') }}</a>
                        </li>--}}

                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <a class="btn" href="http://localhost:5174/">Torna al sito</a>
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Accesso') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Registrazione') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end ms-dropdown" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                                <a class="dropdown-item" href="{{ url('profile') }}">{{ __('Profilo') }}</a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                    {{ __('Esci') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </header>

        <main class="margin-top-header">
            @yield('content')
        </main>
    </div>
</body>

</html>

<style>

    #myHeader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    transition: background-color 0.3s ease;
    box-shadow: 2px 2px 5px rgba(215, 125, 133, 0.75);
    }
    .header-scrolled {
        background-color: rgba(215, 125, 133, 0.95);
    }

    .logo{
        cursor: pointer;
    }

    .logo-img{
        height: 3.75rem;
        margin: .625rem 0;
    }

    .navbar{
        border-bottom: 0.125rem solid var(--custom-black);
    }
    .ms-dropdown{
        border-radius: 0;
    }
    main{
        min-height: calc(100vh - 210px);
    }

    .margin-top-header {
        margin-top: 100px;
    }

</style>