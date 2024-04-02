<tabs>
  @include('admin.contests.tabs.judges')
  @include('admin.contests.tabs.contestants')
  @include('admin.contests.tabs.categories')

  @if (
      $contest->categories()->count() &&
          !$contest->categories()->whereIn('status', ['que', 'scoring'])->count())
    @include('admin.contests.tabs.scores')
    @include('admin.contests.tabs.create-contest-from-results')
  @endif
</tabs>
