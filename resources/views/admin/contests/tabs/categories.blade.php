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
              value="1"
              {{ old('has_criterias') ? 'checked' : null }} />
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
          'class' => 'max-points-percentage-wrapper ' . ((($contest->scoring_system == 'ranking' && !old('has_criterias')) || $contest->scoring_system == 'average') ? '' : 'hidden')
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
              <div class="mt-2">{{ $category->has_criterias ? 'Has' : 'No' }} Criterias</div>
              <div class="mt-2">{{ $category->scoring_system_label }} Scoring System</div>

              @if ($category->max_points_percentage)
                <div class="mt-2 italic">{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
              @endif

              <div class="mt-2">
                @status(['status' => $category->status])
                  {{ config("options.category_statuses.{$category->status}") }}
                @endstatus
              </div>
            </div>
          </a>

          <div class="flex-shrink mt-4 whitespace-nowrap lg:mt-0 lg:pr-4">
            <form method="post"
              action="{{ route('admin.contests.categories.move.up', ['contest' => $contest->id, 'category' => $category->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Up @endbutton
            </form>

            <form method="post"
              action="{{ route('admin.contests.categories.move.down', ['contest' => $contest->id, 'category' => $category->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Down @endbutton
            </form>
          </div>

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
