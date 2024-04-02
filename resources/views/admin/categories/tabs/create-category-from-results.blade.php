<tab-item title="Create Category from Results">
  <form method="post"
    action="{{ route('admin.contests.categories.store-from-score', ['contest' => $contest->id, 'category' => $category->id]) }}"
    class="p-4">
    @csrf

    @formField(['error' => 'name', 'label' => 'Name'])
    <input-picker api="{{ route('admin.categories.index') }}"
      hidden-name="id"
      display-name="name"
      hidden-property="id"
      display-property="name"
      hidden-value="{{ old('id') ?? '' }}"
      display-value="{{ old('name') ?? '' }}"
      placeholder="Enter name of category..."></input-picker>
    @endformField

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

    @formField(['error' => 'scoring_system', 'label' => 'Scoring System', 'class'=> 'block mb-6 scoring-system-wrapper ' . (old('has_criterias') ? '' : 'hidden')])
    {!! Form::select('scoring_system', $contest->scoring_system == 'ranking' ? config('options.scoring_systems') : ['average' => 'Average'], old('scoring_system'), [
        'id' => 'scoring_system',
        'class' => 'block w-full form-select',
        'placeholder' => '- Select Scoring System -',
    ]) !!}
    @endformField

    @formField([
    'error' => 'max_points_percentage',
    'label' => 'Maximum Points / Percentage',
    'class' => 'block mb-6 max-points-percentage-wrapper ' . ((($contest->scoring_system == 'ranking' && !old('has_criterias')) || $contest->scoring_system == 'average') ? '' : 'hidden')
    ])
    <input type="text"
      id="max_points_percentage"
      name="max_points_percentage"
      class="block w-full form-input"
      value="{{ old('max_points_percentage') }}"
      placeholder="Enter maximum points or percentage of category...">
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
        class="form-checkbox" />
    </label>

    @button(['type' => 'submit'])
    Create Category
    @endbutton
  </form>
</tab-item>

@include('admin.categories.form-script')
