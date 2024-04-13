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
    <div class="w-full 3xl:w-3/4">
      @card
        <div class="relative">
          <form class="mb-6"
            method="post"
            action="{{ route('admin.contests.categories.update', ['contest' => $contest->id, 'category' => $category->id]) }}">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-4 mb-6">
              @formField(['label' => 'Name', 'error' => 'name', 'class' => "name-wrapper"])
              <input-picker api="{{ route('admin.categories.index') }}"
                hidden-name="id"
                display-name="name"
                hidden-property="id"
                display-property="name"
                hidden-value="{{ old('id', $category->id) }}"
                display-value="{{ old('name', $category->name) }}"
                placeholder="Enter name of category..."></input-picker>
              @endformField()

              @if ($category->scores()->count())
                <div>
                  <label class="flex items-center h-full space-x-2">
                    <input type="checkbox"
                      disabled
                      class="form-checkbox"
                      {{ $category->has_criterias ? 'checked' : null }} />
                    <span>Has Criterias</span>
                  </label>
                </div>
                @if ($category->scoring_system)
                  <div>
                    {{ $category->scoring_system_label }} Scoring System
                  </div>
                @endif
                @if ($category->max_points_percentage)
                  <div>{{ $category->max_points_percentage }} {{ $category->has_criterias && ($contest->scoring_system == 'ranking' || $category->scoring_system == 'ranking') ? 'points' : '%' }}</div>
                  <div>{{ $category->step }} Step</div>
                @endif
              @else
                @formField(['error' => 'has_criterias', 'class' => 'has-criterias-wrapper'])
                <label class="flex items-center h-full space-x-2 cursor-pointer">
                  <input type="checkbox"
                    id="has_criterias"
                    name="has_criterias"
                    class="form-checkbox"
                    value="1"
                    {{ old('has_criteiras', $category->has_criterias) ? 'checked' : null }} />
                  <span>Has Criterias</span>
                </label>
              @endformField()

              @formField(['label' => 'Scoring System', 'error' => 'scoring_system', 'class'=> 'scoring-system-wrapper ' . (old('has_criterias', $category->has_criterias) ? '' : 'hidden')])
              {!! Form::select('scoring_system', $contest->scoring_system == 'ranking' ? config('options.scoring_systems') : ['average' => 'Average'], old('scoring_system', $category->scoring_system), [
                  'id' => 'scoring_system',
                  'class' => 'block w-full form-select',
                  'placeholder' => '- Select Scoring System -',
              ]) !!}
              @endformField

              @formField([
              'label' => 'Maximum Ponts / Percentage',
              'error' => 'max_points_percentage',
              'class' => 'max-points-percentage-wrapper ' . ((($contest->scoring_system == 'ranking' && !old('has_criterias', $category->has_criterias)) || $contest->scoring_system == 'average') ? '' : 'hidden')
              ])
              <input type="text"
                id="max_points_percentage"
                name="max_points_percentage"
                class="block w-full form-input"
                value="{{ old('max_points_percentage', $category->max_points_percentage) }}"
                placeholder="Enter maximum points or percentage of category..." />
              @endformField

              @formField([
              'label' => 'Step',
              'error' => 'step',
              'class' => 'step-wrapper ' . (old('has_criterias', $category->has_criterias) ? 'hidden' : '')
              ])
              <input type="text"
                id="step"
                name="step"
                class="block w-full form-input"
                value="{{ old('step', $category->step) }}"
                placeholder="Enter step..." />
              @endformField
              @endif
              <div>
                @status(['status' => $category->status])
                  {{ config("options.category_statuses.{$category->status}") }}
                @endstatus
              </div>
            </div>

            @button(['type' => 'submit', 'class' => 'flex-none']) Edit @endbutton
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

@if (!$category->scores()->count())
  @include('admin.categories.form-script')
@endif

@include('admin.scores.remove-script')
