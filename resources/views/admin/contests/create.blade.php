@extends('layouts.admin')
@section('content')
  @breadcrumb(['links' => [['url' => route('admin.contests.index'), 'title' => 'Contests']], 'class' => 'mb-10'])
    @pageHeader()
      Create a New Contest
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
      @card()
        <form method="post"
          action="{{ route('admin.contests.store') }}"
          enctype="multipart/form-data">
          @csrf

          @formField(['label' => 'Name', 'error' => 'name'])
          <input type="text"
            name="name"
            value="{{ old('name') }}"
            class="block w-full form-input"
            placeholder="Enter Contest Name">
          @endformField

          @formField(['label' => 'Description', 'error' => 'description'])
          <textarea name="description"
            class="block w-full resize-none form-textarea"
            rows="3"
            placeholder="Enter Contest Description">{{ old('description') }}</textarea>
          @endformField

          @formField(['label' => 'Scoring System', 'error' => 'scoring_system'])
          {!! Form::select('scoring_system', config('options.scoring_systems'), old('scoring_system'), ['class' => 'block w-full form-select', 'placeholder' => '-']) !!}
          @endformField

          @formField(['label' => 'Logo', 'error' => 'logo'])
          <input type="file"
            name="logo"
            class="block w-full form-input">
          @endformField

          @button(['type' => 'submit'])
          Create
          @endbutton
        </form>
      @endcard
    </div>
  </div>
@endsection
