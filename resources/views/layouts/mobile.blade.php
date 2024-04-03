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
    href="{{ asset('images/favicon.png') }}"
    type="image/png" />

  <!-- Styles -->
  <link href="{{ mix('css/app.css') }}"
    rel="stylesheet">
</head>

<body class="antialiased leading-none">
  <div id="app"
    class="flex flex-col h-screen">
    <div class="flex items-center justify-between flex-none w-full h-24 bg-white border-b shadow">
      <div class="flex-none">
        <a href="{{ route('judge.categories.index') }}"
          class="flex items-center justify-center w-12 h-12 no-underline hover:bg-gray-200">
          @svg('home-solid', 'w-6 h-6 fill-current')
        </a>
      </div>
      <div class="flex items-center justify-center flex-grow font-normal text-center">
        <div class="flex items-center justify-end h-24">
          <img src="{{ asset('images/' . env('LOGO_TEXT_BELOW')) }}"
            class="h-40">
        </div>
        <div class="-ml-8 font-bold">
          {{ config('app.name') }}
        </div>
      </div>
      @yield('navbar-right')
    </div>

    <div class="flex-grow overflow-y-auto">
      @yield('content')
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
  {{-- <script>
        // Failed to execute 'requestFullscreen' on 'Element': API can only be initiated by a user gesture.
		document.body.requestFullscreen();
	</script> --}}
</body>

</html>
