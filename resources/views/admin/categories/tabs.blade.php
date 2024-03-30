<tabs>
  @include('admin.categories.tabs.criterias')
  @include('admin.categories.tabs.judges')
  @include('admin.categories.tabs.contestants')

  @if ($category->status == 'scoring')
    <tab-item title="Real-Time Scores"
      class="">
      <live-score api="{{ route('admin.contests.categories.live', ['contest' => $contest->id, 'category' => $category->id]) }}"></live-score>
    </tab-item>
  @endif

  @if ($category->status == 'done')
    @include('admin.categories.tabs.scores')

    @include('admin.categories.tabs.create-category-from-results')
  @endif
</tabs>
