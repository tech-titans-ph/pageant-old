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

  <link rel="manifest"
    href="{{ asset('site.webmanifest') }}" />

  <!-- Styles -->
  <link href="{{ mix('css/app.css') }}"
    rel="stylesheet">
</head>

<body class="antialiased leading-none">
  <div id="app"
    class="flex flex-col h-screen">
    <div class="flex items-center justify-between flex-none w-full bg-white border-b shadow">
      <div class="w-1/6 h-full">
        <a href="{{ route('judge.categories.index') }}"
          class="flex items-center h-full px-6 text-black no-underline flex-shrink-1 hover:bg-gray-200">
          @svg('home-solid', 'w-12 h-12 fill-current')
        </a>
      </div>
      <div class="flex-grow font-bold leading-normal text-center">
        <h2 class="text-lg ">{{ $judge->contest->name }}</h2>
        <p class="text-sm italic">Welcome, {{ $judge->name }}</p>
        <p>{{ $category->name ?? null }}</p>
      </div>
      <div class="flex items-center justify-end w-1/6 h-full">
        <judge-logout app-name="{{ config('app.name') }}"
          logo-url="{{ asset('images/' . env('LOGO_TEXT_BELOW')) }}"
          logout-url="{{ route('logout') }}"
          csrf-token="{{ csrf_token() }}" />
      </div>
    </div>
    <div class="flex-grow overflow-y-auto">
      @yield('content')
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
