<label class="{{ $class ?? 'block mb-6' }}">
  @if (isset($label))
    <span class="block mb-2 text-gray-700">{{ $label }}</span>
  @endif

  {{ $slot }}

  @if (isset($error))
    @error($error)
      <p class="mt-1 text-sm italic text-red-500">{{ $message }}</p>
    @enderror
  @endif
</label>
