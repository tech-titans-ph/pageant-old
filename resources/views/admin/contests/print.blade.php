@extends('layouts.print')

@section('content')
  @include('admin.scores.contest')

  @include('admin.print-footer', ['model' => $contest])
@endsection
