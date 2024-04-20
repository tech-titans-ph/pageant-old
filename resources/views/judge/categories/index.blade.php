@extends('layouts.mobile')

@section('content')
  <div class="flex items-stretch h-full divide-x">
    <div class="w-1/2 ">
      <img src="{{ $judge->contest->logo_url }}"
        class="object-contain object-center w-full h-full"
        alt="Contestant">
    </div>
    <div class="w-1/2 overflow-y-auto">
      <judge-category api="{{ route('judge.categories.list-categories') }}"></judge-category>
    </div>
  </div>
  <alert-judge api="{{ route('judge.categories.status') }}"></alert-judge>
@endsection
