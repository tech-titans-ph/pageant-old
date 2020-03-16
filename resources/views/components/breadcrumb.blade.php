<div class="flex flex-wrap items-center {{ $class ?? '' }}">
	@foreach($links as $link)
		<div class="mr-1 w-full md:w-auto mb-1 md:mb-0">
			<a href="{{ $link['url'] }}" class="no-underline hover:underline">{{ $link['title'] }}</a> /
		</div>
	@endforeach
	{{ $slot }}
</div>