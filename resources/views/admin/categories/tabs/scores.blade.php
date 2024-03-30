<tab-item title="Scores">
  <ul>
    <li class="flex justify-between p-4">
      @if (!$contest->categories()->whereIn('status', ['que', 'scoring'])->count())
        @buttonLink([
            'href' => route('admin.contests.categories.print', ['contest' => $contest->id, 'category' => $category->id]),
            'attributes' => 'target="_blank"'
        ])
          Print Scores
        @endbuttonLink
        @buttonLink(['href' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Scores'])])
          Summary of Scores
        @endbuttonLink
      @endif
    </li>
    @php
      $top = 0;
    @endphp
    @foreach ($scoredCategoryContestants as $contestant)
      @php
        $top++;
      @endphp
      <li class="p-4 border-t">
        <div class="flex justify-between mx-auto">
          <div class="flex flex-grow">
            <div class="flex-none">
              <img src="{{ $contestant->avatar_url }}"
                class="object-cover object-center w-32 h-32 border rounded-full">
              <div class="mt-1 text-sm font-medium text-center">Top {{ $top }}</div>
            </div>
            <a href="{{ route('admin.contests.categories.category-contestants.show', ['contest' => $contest->id, 'category' => $category->id, 'categoryContestant' => $contestant->id]) }}"
              class="flex flex-col self-center px-4">
              <div class="block mb-2 font-bold">
                # {{ $contestant->contestant->number . ' - ' . $contestant->contestant->name }}
              </div>
              <div class="italic">
                {{ $contestant->contestant->description }}
              </div>
            </a>
          </div>
          <div class="self-center text-6xl font-bold text-green-700 whitespace-no-wrap">
            {{ round($contestant->averagePercentage, 4) }}
          </div>
        </div>
      </li>
    @endforeach
  </ul>
</tab-item>
