@php
	$type = $type ?? 'success';
	
	$alertClass['success'] = 'border-green-500 bg-green-200 text-green-900';
	$alertClass['error'] = 'border-red-500 bg-red-200 text-red-900';
@endphp

<div class="w-full md:w-auto p-4 rounded border-l-4 mb-4 mx-auto md:mx-0 {{ $alertClass[$type] }}">
	{{ $slot }}
</div>