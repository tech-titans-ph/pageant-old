@extends('layouts.admin')
@section('content')
  @breadcrumb([
      'links' => [['url' => route('admin.contests.index'), 'title' => 'Contests']],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Edit Contest
    @endpageHeader
  @endbreadcrumb

  <div class="flex">
    <div class="w-3/4">
      @card
        <form method="post"
          action="{{ route('admin.contests.update', ['contest' => $contest->id]) }}"
          enctype="multipart/form-data">
          @csrf
          @method('PATCH')

          @formField(['label' => 'Name', 'error' => 'name'])
          <input type="text"
            name="name"
            value="{{ old('name') ? old('name') : $contest->name }}"
            class="block w-full mt-1 form-input"
            placeholder="Enter Contest Name">
          @endformField

          @formField(['label' => 'Description', 'error' => 'description'])
          <textarea name="description"
            class="block w-full mt-1 resize-none form-textarea"
            rows="3"
            placeholder="Enter Contest Description">{{ old('description') ? old('description') : $contest->description }}</textarea>
          @endformField

          @if (!$contest->categories()->whereHas('scores')->count())
            @formField(['label' => 'Scoring System', 'error' => 'scoring_system'])
            {!! Form::select('scoring_system', config('options.scoring_systems'), old('scoring_system', $contest->scoring_system), ['class' => 'block w-full form-select', 'placeholder' => '-']) !!}
            @endformField
          @endif

          <label class="block mb-4">
            <img src="{{ Storage::url($contest->logo) }}"
              class="object-contain object-center w-64 h-64 mx-auto bg-white border rounded">
          </label>

          @formField(['label' => 'Logo', 'error' => 'logo'])
          <input type="file"
            name="logo"
            class="block w-full mt-1 form-input">
          @endformField

          @button(['type' => 'submit']) Edit @endbutton
        </form>
      @endcard
    </div>
  </div>
@endsection
