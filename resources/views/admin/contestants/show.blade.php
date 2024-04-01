@extends('layouts.admin')

@section('content')
  @breadcrumb([
      'links' => [['url' => route('admin.contests.index'), 'title' => 'Contests'], ['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Scores']), 'title' => $contest->name]],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Contestant Score
    @endpageHeader
  @endbreadcrumb

  <div class="flex">
    <div class="w-3/4">
      @card()
        @include('admin.scores.contest')
      @endcard
    </div>
  </div>
@endsection
