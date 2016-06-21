<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@lang('system.title')</title>

  <!-- Fonts ->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
  <-- Fonts -->
  <!-- Styles -->
  <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{url('css/login.css')}}">
  {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

</head>
<body id="app-layout">
<img src="{{url('images/bg4.jpg')}}" class="blur"/>
@yield('content')

<!-- JavaScripts -->
<script src="{{url('js/jquery.min-2.2.3.js')}}"></script>
<script src="{{url('js/bootstrap.min-3.3.6.js')}}"></script>
<script src="{{url('js/login.js')}}"></script>
<!-- JavaScripts -->
</body>
</html>