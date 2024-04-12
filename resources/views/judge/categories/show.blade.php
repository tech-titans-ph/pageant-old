@extends('layouts.mobile')

@section('navbar-right')
  <a href="{{ route('logout') }}"
    class="flex items-center justify-center h-full px-4 no-underline hover:bg-gray-200"
    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    Logout
  </a>
  <form id="logout-form"
    action="{{ route('logout') }}"
    method="POST"
    class="hidden">
    {{ csrf_field() }}
  </form>
@endsection

@section('content')
  <div class="mx-auto">
    <div class="p-4 leading-normal text-center border-b">
      <h2 class="text-lg font-bold">{{ $category->contest->name }}</h2>
      <p class="font-semibold text-md">{{ $category->name }}</p>
      <p class="text-sm italic font-thin">Judge: {{ $judge->name }}</p>
      <p class="text-2xl font-bold">Score Results</p>
    </div>
    <div class="md:rounded md:shadow-md mx-auto {{ !$judge->pivot->completed ? 'pb-12' : '' }}">
      @foreach ($contestants as $contestant)
        <a class="flex p-6 border-t"
          href="{{ route('judge.categories.contestants.index', ['category' => $category->id, 'page' => $contestant->order]) }}">
          <img src="{{ $contestant->avatar_url }}"
            class="flex-none object-cover object-center w-16 h-16 mr-4 border rounded-full">
          <div class="self-center flex-grow mr-4 space-y-2">
            <div>#{{ $contestant->order }}</div>
            <div class="font-bold">{{ $contestant->name }}</div>
            <div class="italic">{{ $contestant->alias }}</div>
          </div>
          <div class="self-center flex-shrink text-3xl font-bold text-right text-green-700">
            {{ $contestant->points }}
          </div>
        </a>
      @endforeach
    </div>
  </div>

  @if (!$judge->pivot->completed)
    <div class="fixed bottom-0 flex items-center justify-center w-full h-12 bg-white border-t">
      <div class="px-2">
        <a href="{{ route('judge.categories.edit', ['category' => $category->id]) }}"
          class="flex items-center px-1 py-1 text-white bg-green-600 rounded-full shadow items-around">
          <span class="pl-4">Lock Scores</span>
          <svg class="ml-2"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true">
            <circle cx="12"
              cy="12"
              r="10"></circle>
            <polyline points="12 16 16 12 12 8"></polyline>
            <line x1="8"
              y1="12"
              x2="16"
              y2="12"></line>
          </svg>
        </a>
      </div>
    </div>
    <alert-judge api="{{ route('judge.categories.status') }}"></alert-judge>
  @endif
@endsection
