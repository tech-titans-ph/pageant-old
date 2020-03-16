@php
	$color = $color ?? 'default';

	$colors['default'] = 'bg-green-600 hover:bg-green-500';
	$colors['danger'] = 'bg-red-600 hover:bg-red-500';
@endphp

<a href="{{ $href ?? '#' }}" {{ $attributes ?? '' }} class="inline-block text-center font-medium px-4 py-2 rounded-full shadow text-white focus:outline-none focus:shadow-outline {{ $class ?? '' }} {{ $colors[$color] }}">
	{{ $slot }}
</a>