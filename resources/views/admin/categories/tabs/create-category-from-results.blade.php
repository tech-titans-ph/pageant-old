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

    @formField(['error' => 'percentage', 'label' => 'Percentage'])
    <input type="text"
      name="percentage"
      class="block w-full form-input"
      value="{{ old('percentage') }}"
      placeholder="Enter percentage of category...">
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
