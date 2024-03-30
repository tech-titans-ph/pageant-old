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

<body class="h-screen antialiased leading-none bg-gray-100">
  <div id="app">
    <nav class="py-6 bg-blue-900 shadow">
      <div class="container px-6 mx-auto md:px-0">
        <div class="flex items-center justify-center">
          <div class="mr-6">
            <a href="{{ url('/') }}"
              class="text-lg font-semibold text-gray-100 no-underline">
              {{ config('app.name') }}
            </a>
          </div>
          <div class="flex-1 text-right">
            @guest
              {{-- <a class="p-3 text-sm text-gray-300 no-underline hover:underline" href="{{ route('login') }}">{{ __('Login') }}</a> --}}
              {{-- <a class="p-3 text-sm text-gray-300 no-underline hover:underline" href="{{ route('register') }}">{{ __('Register') }}</a> --}}
            @else
              <span class="pr-4 text-sm text-gray-300">{{ Auth::user()->name }}</span>

              <a href="{{ route('logout') }}"
                class="p-3 text-sm text-gray-300 no-underline hover:underline"
                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
              <form id="logout-form"
                action="{{ route('logout') }}"
                method="POST"
                class="hidden">
                {{ csrf_field() }}
              </form>
            @endguest
          </div>
        </div>
      </div>
    </nav>
    @if (auth()->check())
      <div class="flex">
        <div class="w-auto h-screen p-6 bg-blue-100">
          <ul class="whitespace-no-wrap">
            <li><a href="/pageants">Pageants</a></li>
            <li>
              <p class="font-bold">Settings</p>
              <ul class="pl-4">
                <li><a href="/users">Users</a></li>
                <li><a href="/criterias">Criterias</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="w-screen h-auto p-6">
          @yield('content')
        </div>
      </div>
    @else
      @yield('content')
    @endif

  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
  {{-- <script>
    // Failed to execute 'requestFullscreen' on 'Element': API can only be initiated by a user gesture.
    document.body.requestFullscreen();
  </script> --}}
</body>

</html>
