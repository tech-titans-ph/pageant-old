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
<body class="antialiased leading-none">
	<div id="app">
		<div class="fixed w-full bg-white flex justify-between items-center h-12 flex shadow">
			<div class="flex-none">
				<a href="{{ route('judge.categories.index') }}" class="flex justify-center items-center no-underline block h-12 w-12 hover:bg-gray-200">
					@svg('home-solid', 'w-6 h-6 fill-current')
				</a>
			</div>
			<div class="flex-grow text-center font-normal flex items-center justify-center">
				<img src="{{ asset('images/logo.png')  }}" class="inline-block h-8 mr-2 rounded-full">
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
	<script>
		document.body.requestFullscreen();
	</script>
</body>
</html>