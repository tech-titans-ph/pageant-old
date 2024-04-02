@extends('layouts.mobile')

@section('content')
  <div class="pt-24 mx-auto">
    @if (session('success'))
      <div class="p-6 mx-6 font-bold text-center text-green-700 bg-green-200 border-t-8 border-green-700 rounded-lg">
        {{ session('success') }}
      </div>
      <div class="mt-6 text-center">
        <a href="{{ route('judge.categories.index') }}"
          class="inline-block px-6 py-3 font-bold text-gray-700 bg-gray-300 rounded-full">Select another Category</a>
      </div>
    @elseif(session('error'))
      <div class="p-6 mx-6 font-bold text-center text-red-700 bg-red-200 border-t-8 border-red-700 rounded-lg">
        {{ session('error') }}
      </div>
      <div class="mt-6 text-center">
        <a href="{{ route('judge.categories.show', ['category' => $category->id]) }}"
          class="inline-block px-6 py-3 font-bold text-gray-700 bg-gray-300 rounded-full">Continue Scoring</a>
      </div>
      <div class="mt-6 border-b">
        @foreach ($contestants as $contestant)
          <a class="flex p-6 border-t"
            href="{{ route('judge.categories.contestants.index', ['category' => $category->id, 'page' => $contestant->order]) }}">
            <img src="{{ $contestant->avatar_url }}"
              class="flex-none object-contain object-center w-16 h-16 mr-4 border rounded-full">
            <div class="self-center flex-grow mr-4 font-bold">
              <div class="mb-2">#{{ $contestant->order }}</div>
              <div>{{ $contestant->name }}</div>
            </div>
          </a>
        @endforeach
      </div>
    @else
      <form method="post"
        action="{{ route('judge.categories.update', ['category' => $category->id]) }}"
        class="p-6 mx-6 border border-t-8 rounded-lg">
        @csrf
        @method('PATCH')
        <div class="mb-6 font-bold text-center text-gray-700">
          Are you sure to Lock all of your scores?
        </div>
        <div class="flex justify-center">
          <button type="submit"
            class="inline-block px-6 py-3 mr-6 font-bold text-white bg-green-600 rounded-full shadow">Yes</button>
          <a href="{{ route('judge.categories.show', ['category' => $category->id]) }}"
            class="inline-block px-6 py-3 font-bold text-white bg-red-600 rounded-full shadow">No</a>
        </div>
      </form>
    @endif
  </div>
@endsection
