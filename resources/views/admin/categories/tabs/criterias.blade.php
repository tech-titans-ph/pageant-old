<tab-item title="Criterias">
  <ul>
    <li class="p-4">
      <form method="post"
        action="{{ route('admin.contests.categories.criterias.store', ['contest' => $contest->id, 'category' => $category->id]) }}">
        @csrf
        <div class="grid flex-1 grid-cols-1 gap-4 mr-4 lg:grid-cols-3">
          @formField(['label' => 'Name', 'error' => 'name'])
          <input-picker api="{{ route('admin.criterias.index') }}"
            hidden-name="id"
            display-name="name"
            hidden-property="id"
            display-property="name"
            hidden-value="{{ old('id') ?? '' }}"
            display-value="{{ old('name') ?? '' }}"
            placeholder="Enter name of criteria..."></input-picker>
          @endformField

          @formField(['label' => 'Maximum Points / Percentage', 'error' => 'max_points_percentage'])
          <input type="text"
            name="max_points_percentage"
            class="block w-full form-input"
            value="{{ old('max_points_percentage') }}"
            placeholder="Enter maximum points or percentage of criteria..." />
          @endformField

          @formField(['label' => 'Step', 'error' => 'step'])
          <input type="text"
            name="step"
            class="block w-full form-input"
            value="{{ old('step', 1) }}"
            placeholder="Enter step..." />
          @endformField
        </div>
        <div class="flex flex-col justify-end lg:flex-row">
          @button(['type' => 'submit']) Add Criteria @endbutton
        </div>
      </form>
    </li>

    @forelse ($category->criterias as $criteria)
      <li class="p-4 border-t">
        <div class="flex items-center space-x-4">
          <a href="{{ route('admin.contests.categories.criterias.edit', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}"
            class="flex-grow space-y-2">
            <div class="font-bold">{{ $criteria->name }}</div>
            <div class="italic">{{ $criteria->max_points_percentage }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
            <div>{{ $criteria->step }} Step</div>
          </a>
          <div class="flex items-center flex-none space-x-2">
            <form method="post"
              action="{{ route('admin.contests.categories.criterias.move.up', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Up @endbutton
            </form>

            <form method="post"
              action="{{ route('admin.contests.categories.criterias.move.down', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}"
              class="inline-block btn">
              @csrf
              @method('PATCH')

              @button(['type' => 'submit']) Move Down @endbutton
            </form>
            <form method="post"
              action="{{ route('admin.contests.categories.criterias.destroy', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}"
              class="inline-block @if ($criteria->scores()->count()) remove-score-confirmation-form @endif">
              @csrf
              @method('DELETE')
              <input type="hidden"
                name="column"
                value="criteria_id" />
              <input type="hidden"
                name="value"
                value="{{ $criteria->id }}" />
              <input type="hidden"
                name="auth_password" />
              @if ($errors->{"criteria_id_{$criteria->id}"}->any())
                <div class="mb-1 text-sm italic text-red-500">{{ $errors->{"criteria_id_{$criteria->id}"}->first() }}</div>
              @endif
              @button(['type' => 'submit', 'color' => 'danger']) Remove @endbutton
            </form>
          </div>
        </div>
      </li>
    @empty
      <li class="p-4 border-t">No Available Criteria(s).</li>
    @endforelse
  </ul>
</tab-item>
