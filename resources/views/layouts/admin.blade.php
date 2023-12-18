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

  <link href="{{ mix('css/app.css') }}"
    rel="stylesheet">
</head>

<body class="z-0 font-sans antialiased leading-none text-gray-700 bg-gray-200">
  <div id="app"
    class="flex flex-col h-screen">
    <nav class="flex flex-none py-6 bg-blue-900 shadow">
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
</body>

</html>
