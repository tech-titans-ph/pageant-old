@extends('layouts.print')

@section('content')
  <h2 class="text-xs font-bold">
    Best In {{ $bestin->name }}
    <div class="capitalize">{{ $bestin->group->name }} {{ $bestin->type }}</div>
  </h2>

  @php
    if ($bestin->type == 'category') {
        $category = $bestin->category;
    }

    if ($bestin->type == 'criteria') {
        $criteria = $bestin->criteria;

        $category = $criteria->category;
    }
  @endphp

  @include("admin.bestins.{$bestin->type}")
@endsection
