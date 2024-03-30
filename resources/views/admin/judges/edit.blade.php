@extends('layouts.admin')

@section('content')
  @breadcrumb([
      'links' => [['url' => route('admin.contests.index'), 'title' => 'Contests'], ['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges']), 'title' => $contest->name]],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Edit Judge
    @endpageHeader
  @endbreadcrumb

  <div class="flex">
    <div class="w-3/4">
      @card
        <form method="post"
          action="{{ route('admin.contests.judges.update', ['contest' => $contest->id, 'judge' => $judge->id]) }}">
          @csrf
          @method('PATCH')

          @formField(['label' => 'Name', 'error' => 'name'])
          <input-picker api="{{ route('admin.judges.index') }}"
            hidden-name="name"
            display-name="name"
            hidden-property="name"
            display-property="name"
            hidden-value="{{ old('name', $judge->name) }}"
            display-value="{{ old('name', $judge->name) }}"></input-picker>
          @endformField

          @button(['type' => 'submit']) Edit @endbutton
        </form>
      @endcard
    </div>
  </div>
@endsection
