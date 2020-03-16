<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

		<meta name="mobile-web-app-capable" content="yes">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen antialiased leading-none">
    <div id="app">
        <nav class="bg-blue-900 shadow py-6">
            <div class="container mx-auto px-6 md:px-0">
                <div class="flex items-center justify-center">
                    <div class="mr-6">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                            {{ config('app.name') }}
                        </a>
                    </div>
                    <div class="flex-1 text-right">
                        @guest
                            {{-- <a class="no-underline hover:underline text-gray-300 text-sm p-3" href="{{ route('login') }}">{{ __('Login') }}</a> --}}
                            {{-- <a class="no-underline hover:underline text-gray-300 text-sm p-3" href="{{ route('register') }}">{{ __('Register') }}</a> --}}
                        @else
                            <span class="text-gray-300 text-sm pr-4">{{ Auth::user()->name }}</span>

                            <a href="{{ route('logout') }}" class="no-underline hover:underline text-gray-300 text-sm p-3" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                {{ csrf_field() }}
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
        @if(auth()->check())
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
		<script>
			document.body.requestFullscreen();
		</script>
</body>

</html>