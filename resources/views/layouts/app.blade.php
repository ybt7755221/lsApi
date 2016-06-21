<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@lang('system.title')</title>

  <!-- Fonts >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
  < Fonts -->
  <!-- Styles -->
  <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{url('css/app.css')}}">
  {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

</head>
<body id="app-layout">
<nav class="navbar navbar-inverse navbar-static-top">
  <div class="container">
    <div class="navbar-header">

      <!-- Collapsed Hamburger -->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <!-- Branding Image -->
      <a class="navbar-brand" href="{{ url('/') }}">
        {{ trans('system.title') }}
      </a>
    </div>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <!-- Left Side Of Navbar -->
      <ul class="nav navbar-nav">
        <li><a href="{{ url('/home') }}">{{ trans('system.home') }}</a></li>
        <li><a href="{{ url('/user') }}">{{ trans('system.user') }}</a></li>
        <li><a href="{{ url('/menu') }}">{{ trans('system.category') }}</a></li>
        <li><a href="{{ url('/content') }}">{{ trans('system.content') }}</a></li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="nav navbar-nav navbar-right">
        <!-- Authentication Links -->
        @if (Auth::guest())
          <li><a href="{{ url('/login') }}">{{ trans('system.login') }}</a></li>
        @else
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <ul class="dropdown-menu bg-black text-grey" role="menu">
              <li><a class="text-white" href="{{ url('/logout') }}"><i
                      class="fa fa-btn fa-sign-out"></i>{{ trans('system.logout') }}</a></li>
            </ul>
          </li>
        @endif
      </ul>
    </div>
  </div>
</nav>

@yield('content')

<!-- JavaScripts -->
<script src="{{url('js/jquery.min-2.2.3.js')}}"></script>
<script src="{{url('js/bootstrap.min-3.3.6.js')}}"></script>
<script src="{{url('js/app.js')}}"></script>
@stack('ls-js')
<!-- JavaScripts -->
</body>
</html>
