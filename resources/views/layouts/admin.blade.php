<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ mix('css/style.css') }}">
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">
    <div id="app">
        <nav class="z-50 fixed top-0 w-full bg-blue-900 shadow py-6 h-16">
            <div class="px-6">
                <div class="flex items-center justify-center">
                    <div class="mr-6">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                            {{ config('app.name') }}
                        </a>
                    </div>
                    @if(session('activeContest'))
                        <div class="flex-1 text-left text-white">
                            Active Contest: {{ session('activeContest')['name'] }}
                        </div>
                    @endif
                    <div class="flex-1 text-right">
                        @guestcls
                            <a class="no-underline hover:underline text-gray-300 text-sm p-3" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a class="no-underline hover:underline text-gray-300 text-sm p-3" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @else
                            <span class="text-gray-300 text-sm pr-4">{{ Auth::user()->name }}</span>

                            <a href="{{ route('logout') }}"
                               class="no-underline hover:underline text-gray-300 text-sm p-3"
                               onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                {{ csrf_field() }}
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <div id="sidebar" class="hidden lg:block w-1/5 lg:w-1/4 h-screen">
                <div class="fixed z-40 bg-white h-screen w-1/5 lg:w-1/4 overflow-y-scroll mt-16 border-r-2" style="height: calc(100% - 4rem);">
                    @if(session('activeContest'))
                        <div class="pt-8 px-6">
                            <h5 class="mb-3 lg:mb-2 text-gray-500 uppercase tracking-wide font-bold text-sm lg:text-xs">Contest: {{ session('activeContest')['name'] }}</h5>
                            <ul>
                                <li class="mb-3 lg:mb-1">
                                    <a class="px-2 -mx-2 py-1 relative block hover:text-gray-900 text-gray-600 font-medium" href="/contestants">
                                        <span class="relative">Contestants</span>
                                    </a>
                                </li>
                                <li class="mb-3 lg:mb-1">
                                    <a class="px-2 -mx-2 py-1 relative block hover:text-gray-900 text-gray-600 font-medium" href="/judges">
                                        <span class="relative">Judges</span>
                                    </a>
                                </li>                            
                                <li class="mb-3 lg:mb-1">
                                    <a class="px-2 -mx-2 py-1 relative block hover:text-gray-900 text-gray-600 font-medium" href="/contest-categories">
                                        <span class="relative">Contest Categories</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    <div class="pt-8 px-6">
                        <h5 class="mb-3 lg:mb-2 text-gray-500 uppercase tracking-wide font-bold text-sm lg:text-xs">Settings</h5>
                        <ul>
                            <li class="mb-3 lg:mb-1">
                                <a class="px-2 -mx-2 py-1 relative block hover:text-gray-900 text-gray-600 font-medium" href="/contests">
                                    <span class="relative">Contests</span>
                                </a>
                            </li>
                            <li class="mb-3 lg:mb-1">
                                <a class="px-2 -mx-2 py-1 relative block hover:text-gray-900 text-gray-600 font-medium" href="/criterias">
                                    <span class="relative">Criterias</span>
                                </a>
                            </li>
                            <li class="mb-3 lg:mb-1">
                                <a class="px-2 -mx-2 py-1 relative block hover:text-gray-900 text-gray-600 font-medium" href="/users">
                                    <span class="relative">Users</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- <div class="pt-8 px-6">
                        @for($i=0; $i < 100; $i++)
                            <div class="bg-red-200 h-8 mb-2"></div>
                        @endfor
                    </div> -->
                </div>
            </div>

            <div id="content-wrapper" class="lg:border-l-2 min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">
                <div class="p-16">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
