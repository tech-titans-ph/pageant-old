<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

  <meta name="csrf-token"
    content="{{ csrf_token() }}">

  <meta name="mobile-web-app-capable"
    content="yes">

  <title>{{ $title ?? config('app.name') }}</title>

  <link rel="icon"
    href="{{ asset('images/favicon.png') }}"
    type="image/png" />

  <link rel="manifest"
    href="{{ asset('site.webmanifest') }}" />

  <link href="{{ mix('css/app.css') }}"
    rel="stylesheet">
</head>

<body class="z-0 antialiased leading-none text-gray-700 bg-gray-200">
  <div id="app"
    class="flex flex-col h-screen">
    <nav class="flex-none bg-green-600 shadow ">
      <div class="px-4 lg:px-16">
        <div class="flex items-center justify-between">
          <div class="mr-6">
            <a href="{{ url('/') }}"
              class="flex items-center py-1 text-lg font-semibold text-gray-100 no-underline">
              <div class="flex items-center justify-end">
                <img src="{{ asset('images/' . env('LOGO_TEXT_BELOW_DARK')) }}"
                  class="h-20">
              </div>
              <div>{{ config('app.name') }}</div>
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
										document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>
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
    <div class="flex-grow overflow-y-auto">
      <div class="min-h-full px-4 py-8 lg:px-16">
        @yield('content')
      </div>
    </div>
    <alert-admin api="{{ route('admin.contests.status') }}"></alert-admin>
  </div>

  <script src="{{ mix('js/app.js') }}"></script>

  @stack('scripts')
</body>

</html>
