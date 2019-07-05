<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('layouts.topnav')
        @auth
        <div class="page-wrapper chiller-theme toggled">
            <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
                <i class="fas fa-bars"></i>
            </a>
            <div class="container-fluid">
                @include('layouts.sidebar')
        @else
            <div class="container-fluid">
        @endauth
                <main class="page-content" style="margin-top: 10px;">
                    @yield('content')
                </main>
            </div>
            </div>
        </div>

    </div>

{{--
<script>
    $(function () {

    var setdir= localStorage.setItem('firstdir', {{Request::segment(1)}});
    console.log(setdir);

    var getdir = localStorage.getItem('firstdir');
    console.log(getdir);
    });
</script> --}}
</body>
</html>
