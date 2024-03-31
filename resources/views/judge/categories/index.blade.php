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
  <div class="pt-12 mx-auto">
    <div class="p-4 leading-normal text-center border-b">
      <h2 class="text-lg font-bold">{{ $judge->contest->name }}</h2>
      <p class="text-sm italic font-thin">Judge: {{ $judge->name }}</p>
      <p class="text-sm">Please select a Category below to start scoring.</p>
    </div>
    <div class="mx-auto md:rounded md:shadow-md">
      <div class="justify-center border-t md:flex md:justify-between">
        <div class="w-full mx-auto md:w-1/2">
          <img src="{{ $judge->contest->logo_url }}"
            class="object-contain object-center w-full"
            alt="Contestant">
        </div>
      </div>

      <judge-category api="{{ route('judge.categories.list-categories') }}"></judge-category>
    </div>
  </div>
  <alert-judge api="{{ route('judge.categories.status') }}"></alert-judge>
@endsection
