<tab-item title="Scores">
  <ul>
    <li class="flex justify-between p-4">
      @buttonLink(['href' => route('admin.contests.print', ['contest' => $contest->id]), 'attributes' => 'target="_blank"'])
        Print Scores
      @endbuttonLink
    </li>

    @php
      $top = 0;
    @endphp

    @forelse($scoredContestants as $contestant)
      @php
        $top++;
      @endphp
      <li class="p-4 border-t">
        <a href="{{ route('admin.contests.contestants.show', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
          class="flex flex-wrap lg:flex-no-wrap">
          <div class="flex-none">
            <img src="{{ $contestant->avatar_url }}"
              class="object-cover object-center w-64 h-64 mx-auto border rounded-full lg:w-32 lg:h-32 lg:ml-0 lg:mr-2">
            <div class="mt-1 text-sm font-medium text-center">Top {{ $top }}</div>
          </div>
          <div class="self-center flex-grow mt-4 lg:mt-0">
            <div class="block font-bold">
              # {{ $contestant->order . ' - ' . $contestant->name }}
            </div>
            <div class="mt-2 italic">
              {{ $contestant->alias }}
            </div>
          </div>
          <div class="self-center flex-none w-full text-6xl font-bold text-center text-green-700 whitespace-no-wrap lg:pl-2 lg:w-auto">
            {{ round($contestant->totalPercentage, 4) }}
          </div>
        </a>
      </li>
    @empty
      <li class="p-4 border-t">
        No available Score(s).
      </li>
    @endforelse
  </ul>
</tab-item>
