<tab-item title="Scores">
  <ul>
    <li class="flex justify-between p-4">
      {{-- @if (!$contest->categories()->whereIn('status', ['que', 'scoring'])->count()) --}}
      @if ($category->status == 'done')
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

    @foreach ($category->ranked_contestants as $contestant)
      <li class="p-4 border-t">
        <div class="flex justify-between mx-auto">
          <div class="flex flex-grow">
            <div class="flex-none">
              <img src="{{ $contestant->avatar_url }}"
                class="object-cover object-center w-32 h-32 border rounded-full">
              <div class="mt-1 text-sm font-medium text-center">Top {{ $contestant->ranking }}</div>
            </div>
            <a href="{{ route('admin.contests.categories.contestants.show', ['contest' => $contest->id, 'category' => $category->id, 'contestant' => $contestant->id]) }}"
              class="flex flex-col self-center px-4">
              <div class="block mb-2 font-bold">
                # {{ $contestant->order . ' - ' . $contestant->name }}
              </div>
              <div class="italic">
                {{ $contestant->alias }}
              </div>
            </a>
          </div>

          @if ($category->scoring_system == 'average')
            <div class="self-center text-6xl font-bold text-green-700 whitespace-no-wrap">
              {{ round($contestant->average, 4) }}
            </div>
          @endif
        </div>
      </li>
    @endforeach
  </ul>
</tab-item>
