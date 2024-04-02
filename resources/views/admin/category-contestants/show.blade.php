@extends('layouts.admin')
@section('content')
  @breadcrumb([
      'links' => [
          ['url' => route('admin.contests.index'), 'title' => 'Contests'],
          ['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories']), 'title' => $contest->name],
          ['url' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Scores']), 'title' => $category->name]
      ],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Contestant Score
    @endpageHeader
  @endbreadcrumb

  <div class="flex">
    <div class="w-3/4">
      @card()
        @include('admin.scores.category')
      @endcard
    </div>
  </div>
@endsection
