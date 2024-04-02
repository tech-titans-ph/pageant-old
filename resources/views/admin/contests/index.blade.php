@extends('layouts.admin')
@section('content')
  <div class="flex flex-col items-start justify-between mb-10 md:flex-row md:items-center">
    @pageHeader()
      Contests
    @endpageHeader

    <div class="mt-4 md:mt-0">
      @buttonLink(['href' => route('admin.contests.create')])
        Create a New Contest
      @endbuttonLink
    </div>
  </div>

  @if (session('success'))
    <div class="flex">
      @alert()
      {{ session('success') }}
      @endalert
    </div>
  @endif

  @if (session('error'))
    <div class="flex">
      @alert(['type' => 'error'])
      {{ session('error') }}
      @endalert
    </div>
  @endif

  <div class="flex flex-wrap -mx-2">
    @forelse($contests as $contest)
      <a href="{{ route('admin.contests.show', ['contest' => $contest->id]) }}"
        class="px-2 mb-4 md:w-1/3 lg:w-1/4">
        @card()
          <img src="{{ $contest->logo_url }}"
            class="object-cover object-center w-full h-64 mx-auto bg-white border rounded">
          <div class="mt-4 space-y-2 text-center">
            <div class="text-lg font-bold">{{ $contest->name }}</div>
            <div class="italic font-medium">{{ $contest->description }}</div>
            <div>{{ $contest->scoring_system_label }} Scoring System</div>
          </div>
        @endcard
      </a>
    @empty
      @card()
        No available Contest(s).
      @endcard
    @endforelse
  </div>
@endsection
