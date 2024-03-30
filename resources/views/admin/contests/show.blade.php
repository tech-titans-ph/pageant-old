@extends('layouts.admin')
@section('content')
  @breadcrumb(['links' => [['url' => route('admin.contests.index'), 'title' => 'Contests']], 'class' => 'mb-10'])
    @pageHeader()
      Contest Details
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
  <div class="flex">
    <div class="w-full lg:w-3/4">
      @card()
        <div class="flex flex-wrap mb-4 lg:flex-no-wrap">
          <img src="{{ Storage::url($contest->logo) }}"
            class="flex-none object-contain object-center w-64 h-64 mx-auto border rounded lg:mr-4">
          <div class="self-center flex-grow mt-4 space-y-2 lg:mt-0">
            <div class="text-lg font-bold">{{ $contest->name }}</div>
            <div class="italic font-medium">{{ $contest->description }}</div>
            <div>{{ $contest->scoring_system_label }} Scoring System</div>
          </div>
          <div class="self-start order-first w-full pl-2 mb-4 text-right whitespace-no-wrap lg:w-auto lg:flex-none lg:order-none lg:mb-0">
            @buttonLink(['href' => route('admin.contests.edit', ['contest' => $contest->id])])
              Edit
            @endbuttonLink
            <form method="post"
              action="{{ route('admin.contests.destroy', ['contest' => $contest->id]) }}"
              class="inline-block">
              @csrf
              @method('DELETE')
              @button(['type' => 'submit', 'color' => 'danger']) Delete @endbutton
            </form>
          </div>
        </div>
        <tabs>
          <tab-item title="Judges">
            <ul>
              <li class="p-4">
                <form method="post"
                  action="{{ route('admin.contests.judges.store', ['contest' => $contest->id, 'activeTab' => 'Judges']) }}"
                  class="flex flex-wrap items-start lg:flex-no-wrap">
                  @csrf
                  @formField(['error' => 'name', 'class' => 'block flex-grow lg:mr-4 mb-6 lg:mb-0'])
                  <div>
                    <input-picker api="{{ route('admin.judges.index') }}"
                      hidden-name="user_id"
                      display-name="name"
                      hidden-property="id"
                      display-property="name"
                      hidden-value="{{ old('user_id') ?? '' }}"
                      display-value="{{ old('name') ?? '' }}"
                      placeholder="Enter name of judge..."></input-picker>

                    @error('user_id')
                      <div class="mt-4 text-xs italic text-red-500">{{ $message }}</div>
                    @enderror
                  </div>
                  @endformField

                  @button(['type' => 'submit', 'class' => 'flex-none'])
                  Add Judge
                  @endbutton
                </form>
              </li>
              @forelse ($contest->judges as $judge)
                <li class="p-4 border-t">
                  <div class="flex flex-col items-start lg:flex-row lg:items-center">
                    <a href="{{ route('admin.contests.judges.edit', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
                      class="flex-grow font-bold lg:pr-2">
                      {{ $judge->name }}
                    </a>
                    <div class="flex justify-between flex-none w-full mt-4 whitespace-no-wrap lg:w-auto lg:mt-0">
                      @buttonLink(['href' => route('admin.contests.judges.login', ['contest' => $contest->id, 'judge' => $judge->id]), 'class' => 'mr-2'])
                        Login
                      @endbuttonLink
                      <form method="post"
                        action="{{ route('admin.contests.judges.destroy', ['contest' => $contest->id, 'judge' => $judge->id]) }}"
                        class="inline-block btn">
                        @csrf
                        @method('DELETE')
                        @button(['type' => 'submit', 'color' => 'danger'])
                        Remove
                        @endbutton
                      </form>
                    </div>
                  </div>
                </li>
              @empty
                <li class="p-4 border-t">No available Judge(s).</li>
              @endforelse
            </ul>
          </tab-item>
          <tab-item title="Contestants">
            <ul>
              <li class="p-4">
                @buttonLink(['href' => route('admin.contests.contestants.create', ['contest' => $contest->id])])
                  Create a New Contestant
                @endbuttonLink
              </li>
              @forelse ($contest->contestants as $contestant)
                <li class="flex flex-wrap p-4 border-t lg:flex-no-wrap">
                  <a href="{{ route('admin.contests.contestants.edit', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
                    class="flex flex-wrap flex-grow lg:flex-no-wrap lg:pr-4">
                    <img src="{{ Storage::url($contestant->picture) }}"
                      class="flex-none object-cover object-center w-64 h-64 mx-auto border rounded-full lg:w-32 lg:h-32 lg:mx-0">
                    <div class="self-center flex-grow px-0 mt-4 lg:pl-4 lg:mt-0">
                      <div class="font-bold">
                        # {{ $contestant->number . ' - ' . $contestant->name }}
                      </div>
                      <div class="mt-2 italic">
                        {{ $contestant->description }}
                      </div>
                    </div>
                  </a>
                  <div class="flex-none w-full mt-4 md:w-auto lg:pl-4 lg:mt-0">
                    <form method="post"
                      action="{{ route('admin.contests.contestants.destroy', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
                      class="inline-block">
                      @csrf
                      @method('DELETE')
                      @button(['type' => 'submit', 'color' => 'danger']) Delete @endbutton
                    </form>
                  </div>
                </li>
              @empty
                <li class="p-4 border-t">No available Contestant(s).</li>
              @endforelse
            </ul>
          </tab-item>
          <tab-item title="Categories">
            <ul>
              <li class="p-4">
                <form method="post"
                  action="{{ route('admin.contests.categories.store', ['contest' => $contest->id, 'activeTab' => 'Categories']) }}">
                  @csrf

                  <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    @formField(['error' => 'name'])
                    <input-picker api="{{ route('admin.categories.index') }}"
                      hidden-name="id"
                      display-name="name"
                      hidden-property="id"
                      display-property="name"
                      hidden-value="{{ old('id') ?? '' }}"
                      display-value="{{ old('name') ?? '' }}"
                      placeholder="Enter name of category..."></input-picker>
                    @endformField()

                    @formField(['error' => 'has_criterias'])
                    <label class="flex items-center h-full space-x-2 cursor-pointer">
                      <input type="checkbox"
                        id="has_criterias"
                        name="has_criterias"
                        class="form-checkbox"
                        value="1" />
                      <span>Has Criterias</span>
                    </label>
                    @endformField()

                    @formField(['error' => 'scoring_system', 'class'=> 'scoring-system-wrapper ' . (old('has_criterias') ? '' : 'hidden')])
                    {!! Form::select('scoring_system', $contest->scoring_system == 'ranking' ? config('options.scoring_systems') : ['average' => 'Average'], old('scoring_system'), [
                        'id' => 'scoring_system',
                        'class' => 'block w-full form-select',
                        'placeholder' => '- Select Scoring System -',
                    ]) !!}
                    @endformField

                    @formField([
                    'error' => 'max_points_percentage',
                    'class' => 'max-points-percentage-wrapper ' . (old('has_criterias') && ($contest->scoring_system == 'ranking' || old('scoring_system') == 'ranking') ? 'hidden' : '')
                    ])
                    <input type="text"
                      id="max_points_percentage"
                      name="max_points_percentage"
                      class="block w-full form-input"
                      value="{{ old('max_points_percentage') }}"
                      placeholder="Enter maximum points or percentage of category...">
                    @endformField
                  </div>

                  <div class="mt-4">
                    @button(['type' => 'submit', 'class' => 'flex-none'])
                    Add Category
                    @endbutton
                  </div>
                </form>
              </li>
              @forelse ($contest->categories as $category)
                <li class="p-4 border-t">
                  <div class="flex flex-wrap lg:flex-no-wrap">
                    <a href="{{ route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id]) }}"
                      class="flex-grow lg:pr-4">
                      <div class="flex flex-col">
                        <div class="font-bold">{{ $category->name }}</div>
                        <div class="mt-2 italic">{{ $category->percentage }}%</div>
                        <div class="mt-2">
                          @status(['status' => $category->status])
                            {{ $status[$category->status] }}
                          @endstatus
                        </div>
                      </div>
                    </a>
                    <div class="flex-shrink mt-4 whitespace-no-wrap lg:mt-0">
                      <div class="pb-1">
                        @if ($category->status == 'que')
                          <form method="post"
                            action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'contest']) }}"
                            class="inline-block mr-1">
                            @csrf
                            @method('PATCH')
                            @button(['type' => 'submit']) Start Scoring @endbutton
                          </form>
                        @elseif($category->status == 'scoring')
                          <form method="post"
                            action="{{ route('admin.contests.categories.finish', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'contest']) }}"
                            class="inline-block mr-1">
                            @csrf
                            @method('PATCH')
                            @button(['type' => 'submit']) Finish Scoring @endbutton
                          </form>
                        @elseif($category->status === 'done')
                          <form method="post"
                            action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'contest']) }}"
                            class="inline-block mr-1">
                            @csrf
                            @method('PATCH')
                            @button(['type' => 'submit']) Restart Scoring @endbutton
                          </form>
                        @endif
                      </div>
                      <div>
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
                  </div>
                </li>
              @empty
                <li class="p-4 border-t">No available Category.</li>
              @endforelse
            </ul>
          </tab-item>
          @if (!$contest->categories()->whereIn('status', ['que', 'scoring'])->count())
            <tab-item title="Scores">
              <ul>
                <li class="flex justify-between p-4">
                  @buttonLink(['href' => route('admin.contests.print', ['contest' => $contest->id]), 'attributes' => 'target="_blank"'])
                    Print Scores
                  @endbuttonLink
                </li>
                @php
                  $top = 0;
                @endphp
                @forelse($scoredContestants as $contestant)
                  @php
                    $top++;
                  @endphp
                  <li class="p-4 border-t">
                    <a href="{{ route('admin.contests.contestants.show', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
                      class="flex flex-wrap lg:flex-no-wrap">
                      <div class="flex-none">
                        <img src="{{ Storage::url($contestant->picture) }}"
                          class="object-cover object-center w-64 h-64 mx-auto border rounded-full lg:w-32 lg:h-32 lg:ml-0 lg:mr-2">
                        <div class="mt-1 text-sm font-medium text-center">Top {{ $top }}</div>
                      </div>
                      <div class="self-center flex-grow mt-4 lg:mt-0">
                        <div class="block font-bold">
                          # {{ $contestant->number . ' - ' . $contestant->name }}
                        </div>
                        <div class="mt-2 italic">
                          {{ $contestant->description }}
                        </div>
                      </div>
                      <div class="self-center flex-none w-full text-6xl font-bold text-center text-green-700 whitespace-no-wrap lg:pl-2 lg:w-auto">
                        {{ round($contestant->totalPercentage, 4) }}
                      </div>
                    </a>
                  </li>
                @empty
                  <li class="p-4 border-t">
                    No available Score(s).
                  </li>
                @endforelse
              </ul>
            </tab-item>
            <tab-item title="Create Contest from Results">
              <form method="post"
                action="{{ route('admin.contests.store-from-score', ['contest' => $contest->id]) }}"
                enctype="multipart/form-data"
                class="p-4">
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
          @formField(['label' => 'Logo', 'error' => 'logo'])
          <input type="file"
            name="logo"
            class="block w-full form-input">
          @endformField
          @formField(['error' => 'contestant_count', 'label' => 'Top Number of Contestants'])
          <input type="text"
            name="contestant_count"
            class="block w-full form-input"
            value="{{ old('contestant_count') }}"
            placeholder="Enter top number of contestants...">
          @endformField
          <label class="flex items-center mb-6">
            <div class="mr-4">Include Judges</div>
            <input type="checkbox"
              name="include_judges"
              value="1"
              checked="1"
              class="form-checkbox">
          </label>
          @button(['type' => 'submit'])
          Create Contest
          @endbutton
          </form>
          </tab-item>
          @endif
        </tabs>
      @endcard
    </div>
  </div>
@endsection

@push('scripts')
  <script type="text/javascript">
    window.addEventListener('load', () => {
      let hasCriterias = document.querySelector('#has_criterias');

      let scoringSystem = document.querySelector('#scoring_system');

      let maxPointsPercentage = document.querySelector('#max_points_percentage');

      let scoringSystemWrapper = document.querySelector('.scoring-system-wrapper');

      let maxPointsPercentageWrapper = document.querySelector('.max-points-percentage-wrapper');

      let contestScoringSystem = "{{ $contest->scoring_system }}";

      hasCriterias.addEventListener('change', function(event) {
        scoringSystem.value = '';

        scoringSystemWrapper.classList.add('hidden');

        if (contestScoringSystem == 'ranking') {
          maxPointsPercentageWrapper.classList.remove('hidden');
        }

        if (event.target.checked) {
          scoringSystemWrapper.classList.remove('hidden');
        }
      });

      scoringSystem.addEventListener('change', function(event) {
        maxPointsPercentageWrapper.classList.add('hidden');

        if (event.target.value == 'average') {
          maxPointsPercentageWrapper.classList.remove('hidden');
        } else {
          maxPointsPercentage.value = '';
        }
      });
    });
  </script>
@endpush
