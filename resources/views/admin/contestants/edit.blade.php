@extends('layouts.admin')
@section('content')
  @breadcrumb([
      'links' => [['url' => route('admin.contests.index'), 'title' => 'Contests'], ['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Contestants']), 'title' => $contest->name]],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Edit Contestant
    @endpageHeader
  @endbreadcrumb

  <div class="flex">
    <div class="w-3/4">
      @card
        <form method="post"
          action="{{ route('admin.contests.contestants.update', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
          class="mx-auto form"
          enctype="multipart/form-data">
          @csrf
          @method('PATCH')

          @formField(['label' => 'Full Name', 'error' => 'name'])
          <input type="text"
            name="name"
            value="{{ old('name', $contestant->name) }}"
            class="block w-full mt-1 form-input"
            placeholder="Enter Contestant Full Name">
          @endformField

          @formField(['label' => 'Alias', 'error' => 'alias'])
          <input type="text"
            name="alias"
            value="{{ old('alias', $contestant->alias) }}"
            class="block w-full mt-1 resize-none form-textarea"
            placeholder="Enter Contestant Alias" />
          @endformField

          @formField()
          <img src="{{ $contestant->avatar_url }}"
            class="object-contain object-center w-full border">
          @endformField

          @formField(['label' => 'Change Profile Picture', 'error' => 'avatar'])
          <input type="file"
            name="avatar"
            class="block w-full mt-1 form-input">
          @endformField

          @button(['type' => 'submit']) Edit @endbutton
        </form>
      @endcard
    </div>
  </div>
@endsection
