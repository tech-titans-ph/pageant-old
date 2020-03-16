@php
	$statuses = [
		'que' => 'bg-gray-300',
		'scoring' => 'bg-yellow-300',
		'done' => 'bg-green-300',
	];

	$status = $status ?? 'que';
@endphp

<div class="inline-block font-normal rounded px-2 py-1 {{ $statuses[$status] }} {{ $class ?? '' }}">
	{{ $slot }}
</div>