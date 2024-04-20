@extends('layouts.print')

@section('content')
  <h2 class="text-xs font-bold">Category Scores</h2>

  @include('admin.scores.category')

  @include('admin.print-footer', ['model' => $category])
@endsection
