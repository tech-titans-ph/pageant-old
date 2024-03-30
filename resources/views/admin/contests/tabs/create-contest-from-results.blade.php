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
      class="block w-full form-input" />
    @endformField

    @formField(['error' => 'contestant_count', 'label' => 'Top Number of Contestants'])
    <input type="text"
      name="contestant_count"
      class="block w-full form-input"
      value="{{ old('contestant_count') }}"
      placeholder="Enter top number of contestants..." />
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
    Create Contest
    @endbutton
  </form>
</tab-item>
