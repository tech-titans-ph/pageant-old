<label class="{{ $class ?? 'block mb-6' }}">
	@if(isset($label))
		<span class="block text-gray-700 mb-2">{{ $label }}</span>
	@endif
	{{ $slot }}
	@if(isset($error))
		@error($error)
			<p class="text-red-500 text-xs italic mt-4">{{ $message }}</p>
		@enderror
	@endif
</label>