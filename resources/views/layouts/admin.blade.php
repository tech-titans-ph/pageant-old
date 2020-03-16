<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="mobile-web-app-capable" content="yes">

	<title>{{ $title ?? config('app.name') }}</title>

	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="antialiased leading-none bg-gray-200 text-gray-700 z-0">
	<div id="app" class="flex flex-col h-screen">
		<nav class="flex flex-none bg-blue-900 shadow py-6">
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
										document.getElementById('logout-form').submit();">
										{{ __('Logout') }}
									</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
											{{ csrf_field() }}
									</form>
							@endguest
					</div>
				</div>
			</div>
		</nav>
		<div class="flex-grow overflow-y-auto">
			<div class="min-h-full px-4 lg:px-16 py-8">
				@yield('content')
			</div>
		</div>
		<alert-admin api="{{ route('admin.contests.status') }}"></alert-admin>
	</div>

	<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>