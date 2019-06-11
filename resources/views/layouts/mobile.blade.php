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
</head>
<body class="antialiased leading-none">
	<div id="app">
		<div class="fixed w-full bg-white flex justify-between items-center h-12 flex shadow">
			<div class="flex items-center">
				<a href="#" class="flex justify-center items-center no-underline block h-12 w-12 hover:bg-gray-200">
					<svg class="feather feather-menu sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-reactid="791">
						<line x1="3" y1="12" x2="21" y2="12"></line>
						<line x1="3" y1="6" x2="21" y2="6"></line>
						<line x1="3" y1="18" x2="21" y2="18"></line>
					</svg>
				</a>
				<div class="font-normal pl-2">Contest<span class="font-bold">Hub</span></div>
			</div>
			<a
				href="{{ route('logout') }}"
				class="px-4 flex justify-center items-center no-underline block h-full hover:bg-gray-200"
				onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
			>
				Logout
			</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
				{{ csrf_field() }}
			</form>
		</div>

		@yield('content')
	</div>

	<!-- Scripts -->
	<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>