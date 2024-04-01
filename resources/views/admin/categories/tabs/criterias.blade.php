<tab-item title="Criterias">
  <ul>
    <li class="p-4">
      <form method="post"
        action="{{ route('admin.contests.categories.criterias.store', ['contest' => $contest->id, 'category' => $category->id]) }}"
        class="flex items-start">
        @csrf

        <div class="flex flex-grow mr-4">
          @formField(['error' => 'name', 'class' => 'w-1/2'])
          <input-picker api="{{ route('admin.criterias.index') }}"
            hidden-name="id"
            display-name="name"
            hidden-property="id"
            display-property="name"
            hidden-value="{{ old('id') ?? '' }}"
            display-value="{{ old('name') ?? '' }}"
            placeholder="Enter name of criteria..."></input-picker>
          @endformField

          @formField(['error' => 'max_points_percentage', 'class' => 'w-1/2 ml-4'])
          <input type="text"
            name="max_points_percentage"
            class="block w-full form-input"
            value="{{ old('max_points_percentage') }}"
            placeholder="Enter maximum points or percentage of criteria..." />
          @endformField
        </div>

        @button(['type' => 'submit', 'class' => 'flex-none']) Add Criteria @endbutton
      </form>
    </li>

    @forelse ($category->criterias as $criteria)
      <li class="p-4 border-t">
        <div class="flex items-center">
          <a href="{{ route('admin.contests.categories.criterias.edit', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}"
            class="flex-grow pr-4">
            <div class="font-bold">{{ $criteria->name }}</div>
            <div class="mt-2 italic">{{ $criteria->max_points_percentage }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
          </a>
          <form method="post"
            action="{{ route('admin.contests.categories.criterias.destroy', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}"
            class="flex-none inline-block @if ($criteria->scores()->count()) remove-score-confirmation-form @endif">
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
      </li>
    @empty
      <li class="p-4 border-t">No Available Criteria(s).</li>
    @endforelse
  </ul>
</tab-item>
