@extends('layouts.admin')
@section('content')
  @breadcrumb([
      'links' => [
          ['url' => route('admin.contests.index'), 'title' => 'Contests'],
          ['url' => route('admin.contests.show', ['contest' => $category->contest_id, 'activeTab' => 'Categories']), 'title' => $category->contest->name]
      ],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Category Details
    @endpageHeader
  @endbreadcrumb

  @if (session('success'))
    <div class="flex">
      @alert()
      {{ session('success') }}
      @endalert
    </div>
  @endif

  @if (session('error'))
    <div class="flex">
      @alert(['type' => 'error'])
      {{ session('error') }}
      @endalert
    </div>
  @endif

  @error('judge_id')
    <div class="flex">
      @alert(['type' => 'error'])
      {{ $message }}
      @endalert
    </div>
  @enderror

  @error('contestant_id')
    <div class="flex">
      @alert(['type' => 'error'])
      {{ $message }}
      @endalert
    </div>
  @enderror

  <div class="flex">
    <div class="w-3/4">
      @card
        <div class="relative">
          <form class="mb-6"
            method="post"
            action="{{ route('admin.contests.categories.update', ['contest' => $contest->id, 'category' => $category->id]) }}">
            @csrf
            @method('PATCH')
            @formField(['label' => 'Name', 'error' => 'name'])
            <input-picker api="{{ route('admin.categories.index') }}"
              hidden-name="id"
              display-name="name"
              hidden-property="id"
              display-property="name"
              hidden-value="{{ old('id') ?? $category->id }}"
              display-value="{{ old('name') ?? $category->name }}"
              placeholder="Enter name of category..."></input-picker>
            @endformField()
            @formField(['label' => 'Percentage', 'error' => 'percentage'])
            <input type="text"
              name="percentage"
              class="block w-full form-input"
              value="{{ old('percentage') ?? $category->percentage }}"
              placeholder="Enter percentage of category...">
            @endformField
            <div class="mb-6">
              @status(['status' => $category->status])
                {{ config("options.category_statuses.{$category->status}") }}
              @endstatus
            </div>
            @button(['type' => 'submit', 'class' => 'flex-none'])
            Edit
            @endbutton
          </form>
          <div class="absolute bottom-0 right-0">
            @if ($category->status == 'que')
              <form method="post"
                action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'category']) }}"
                class="inline-block mr-1">
                @csrf
                @method('PATCH')
                @button(['type' => 'submit']) Start Scoring @endbutton
              </form>
            @elseif($category->status == 'scoring')
              <form method="post"
                action="{{ route('admin.contests.categories.finish', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'category']) }}"
                class="inline-block mr-1">
                @csrf
                @method('PATCH')
                @button(['type' => 'submit']) Finish Scoring @endbutton
              </form>
            @elseif($category->status === 'done')
              <form method="post"
                action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'category']) }}"
                class="inline-block mr-1">
                @csrf
                @method('PATCH')
                @button(['type' => 'submit']) Restart Scoring @endbutton
              </form>
            @endif

            <form method="post"
              action="{{ route('admin.contests.categories.destroy', ['contest' => $contest->id, 'category' => $category->id]) }}"
              class="inline-block">
              @csrf
              @method('DELETE')
              @button(['type' => 'Submit', 'color' => 'danger'])
              Remove
              @endbutton
            </form>
          </div>
        </div>

        @include('admin.categories.tabs')
      @endcard
    </div>
  </div>
@endsection
