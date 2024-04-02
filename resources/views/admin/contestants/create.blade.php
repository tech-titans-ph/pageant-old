@extends('layouts.admin')
@section('content')
  @breadcrumb([
      'links' => [['url' => route('admin.contests.index'), 'title' => 'Contests'], ['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Contestants']), 'title' => $contest->name]],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Create a New Contestant
    @endpageHeader
  @endbreadcrumb

  @if (session('success'))
    <div class="flex">
      @alert()
      {{ session('success') }}
      @endalert
    </div>
  @endif

  <div class="flex">
    <div class="w-3/4">
      @card
        <form method="post"
          action="{{ route('admin.contests.contestants.store', ['contest' => $contest->id]) }}"
          enctype="multipart/form-data">
          @csrf

          @formField(['label' => 'Full Name', 'error' => 'name'])
          <input type="text"
            name="name"
            value="{{ old('name') }}"
            class="block w-full mt-1 form-input"
            placeholder="Enter Contestant Full Name">
          @endformField

          @formField(['label' => 'Alias', 'error' => 'alias'])
          <input type="text"
            name="alias"
            value="{{ old('alias') }}"
            class="block w-full mt-1 form-input"
            placeholder="Enter Contestant Alias">
          @endformField

          @formField(['label' => 'Profile Picture', 'error' => 'avatar'])
          <input type="file"
            name="avatar"
            class="block w-full mt-1 form-input">
          @endformField

          @button(['type' => 'submit']) Create @endbutton
        </form>
      @endcard
    </div>
  </div>
@endsection
