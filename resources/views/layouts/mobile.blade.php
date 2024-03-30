<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token"
    content="{{ csrf_token() }}">

  <meta name="mobile-web-app-capable"
    content="yes">

  <title>{{ config('app.name') }}</title>

  <link rel="icon"
    href="{{ asset('images/' . env('LOGO_ONLY')) }}" />

  <!-- Styles -->
  <link href="{{ mix('css/app.css') }}"
    rel="stylesheet">
</head>

<body class="antialiased leading-none">
  <div id="app">
    <div class="fixed flex items-center justify-between w-full h-12 bg-white shadow">
      <div class="flex-none">
        <a href="{{ route('judge.categories.index') }}"
          class="flex items-center justify-center w-12 h-12 no-underline hover:bg-gray-200">
          @svg('home-solid', 'w-6 h-6 fill-current')
        </a>
      </div>
      <div class="flex items-center justify-center flex-grow font-normal text-center">
        <img src="{{ asset('images/logo.png') }}"
          class="inline-block h-8 mr-2 rounded-full">
        <div>
          Contest<span class="font-bold">Hub</span>
        </div>
      </div>
      @yield('navbar-right')
    </div>

    @yield('content')
  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
  {{-- <script>
        // Failed to execute 'requestFullscreen' on 'Element': API can only be initiated by a user gesture.
		document.body.requestFullscreen();
	</script> --}}
</body>

</html>
