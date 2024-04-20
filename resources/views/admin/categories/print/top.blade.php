@extends('layouts.print')

@section('content')
  <h2 class="text-xs font-bold">
    <div>TOP {{ request('top') }}</div>
    <div>{{ $category->name }}</div>
    <div>{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
  </h2>
  <br />
  <ul class="border border-black divide-y divide-black">
    @foreach ($category->ranked_contestants->slice(0, request('top')) as $contestant)
      <li class="p-2"
        style="page-break-inside: avoid;">
        <div class="flex justify-between space-x-16">
          <div class="flex flex-grow">
            <div class="flex-none">
              <img src="{{ $contestant->avatar_url }}"
                class="object-cover object-center w-32 h-32 border rounded-full">
            </div>
            <div class="flex flex-col self-center px-4 text-base font-bold">
              <div>Top {{ $contestant->ranking }}</div>
              <div class="block mb-2"># {{ $contestant->order . ' - ' . $contestant->name }}</div>
              <div class="italic">
                {{ $contestant->alias }}
              </div>
            </div>
          </div>

          @if ($category->scoring_system == 'average' || $contest->scoring_system == 'average')
            <div class="self-center flex-shrink-0 text-6xl font-bold text-green-700 whitespace-no-wrap">
              {{ round($contestant->average, 4) }}
            </div>
          @endif
        </div>
      </li>
    @endforeach
  </ul>
  @include('admin.print-footer', ['model' => $category])
@endsection
