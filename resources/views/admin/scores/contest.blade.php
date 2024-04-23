{{-- @props(['contest']) --}}

<h2 class="text-xs font-bold">Summary of Scores</h2>
<br />
<table class="w-full">
  <thead>
    <tr>
      <th class="px-2 py-1 border border-black"
        {{-- colspan="{{ $contest->scoring_system == 'average' ? 1 : 1 }}" --}}
        rowspan="2">Contestants</th>
      @foreach ($contest->categories as $category)
        <th class="px-2 py-1 border border-black"
          colspan="{{ $category->judges->count() + ($contest->scoring_system == 'ranking' ? 1 : 0) }}">
          <div>{{ $category->name }}</div>

          @if ($category->max_points_percentage)
            <div>{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
          @endif
        </th>
      @endforeach

      <th class="px-2 py-1 text-center border border-black"
        rowspan="2">{{ $contest->scoring_system == 'ranking' ? 'SUM OF RANK' : 'TOTAL' }}</th>
    </tr>
    <tr>
      @foreach ($contest->categories as $category)
        @foreach ($category->judges as $judge)
          <th class="px-2 py-1 border border-black">
            {!! str_replace(' ', '<br />', $judge->name) !!}
          </th>
        @endforeach

        @if ($contest->scoring_system == 'ranking')
          <th class="px-2 py-1 border border-black">
            RANKING
          </th>
        @endif
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($contest->ranked_contestants as $contestantIndex => $contestant)
      @if ($contest->scoring_system == 'average')
        <tr style="{{ /* $contestantIndex ? 'page-break-before: avoid' : */ 'page-break-inside: avoid' }};"
          class="{{ $loop->first ? 'bg-green-100' : '' }}">
          @if (Str::endsWith(Route::currentRouteName(), '.print'))
            <td class="px-2 py-1 text-center align-top border border-black {{ $loop->first ? 'font-bold text-sm' : '' }}">
              <div>Top {{ $contestant->ranking }}</div>
              <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
              <div>{{ $contestant->alias }}</div>
            </td>
          @else
            <td class="w-40 px-2 py-1 text-center align-top border border-black whitespace-nowrap {{ $loop->first ? 'font-bold text-sm' : '' }}">
              <img src="{{ $contestant->avatar_url }}"
                class="object-cover object-center w-24 h-24 mx-auto mb-1 border border-black rounded-full" />
              <div>Top {{ $contestant->ranking }}</div>
            </td>
            <td class="px-2 py-1 align-top border border-black {{ $loop->first ? 'font-bold text-sm' : '' }}">
              <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
              <div>{{ $contestant->alias }}</div>
            </td>
          @endif

          @foreach ($contest->categories as $category)
            @php
              $categoryScore = $contestant->category_scores->firstWhere('id', '=', $category->id);
            @endphp

            @foreach ($category->judges as $judge)
              <td class="px-2 py-1 text-center align-middle border border-black">
                @php
                  $judgeScore = $categoryScore->judge_scores->firstWhere('judge_id', '=', $judge->id);
                @endphp

                {{ round($judgeScore['points_percentage'] ?? 0, 4) }}
              </td>
            @endforeach
          @endforeach

          <th class="px-2 py-1 align-bottom border border-black"
            rowspan="2">{{ round($contestant->average_sum, 4) }}</th>
        </tr>
      @endif

      @if ($contest->scoring_system == 'ranking')
        <tr class="{{ $loop->first ? 'bg-green-100' : '' }}">
          @if (Str::endsWith(Route::currentRouteName(), '.print'))
            <td class="px-2 py-1 text-center align-top border border-black whitespace-nowrap {{ $loop->first ? 'font-bold text-xs' : '' }}">
              <div>Top {{ $contestant->ranking }}</div>
              <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
              <div>{{ $contestant->alias }}</div>
            </td>
          @else
            <td class="w-40 px-2 py-1 text-center align-top border border-black whitespace-nowrap {{ $loop->first ? 'font-bold text-xs' : '' }}">
              <img src="{{ $contestant->avatar_url }}"
                class="object-cover object-center w-24 h-24 mx-auto mb-1 border border-black rounded-full" />
              <div>Top {{ $contestant->ranking }}</div>
            </td>
            <td class="px-2 py-1 align-top border border-black {{ $loop->first ? 'font-bold text-xs' : '' }}">
              <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
              <div>{{ $contestant->alias }}</div>
            </td>
          @endif

          @foreach ($contest->categories as $category)
            @php
              $categoryScore = $contestant->category_scores->firstWhere('id', '=', $category->id);

              $categoryContestant = $category->ranked_contestants->firstWhere('id', '=', $contestant->id);
            @endphp

            @foreach ($category->judges as $judge)
              @php
                $judgeScore = $categoryScore->judge_scores->firstWhere('judge_id', '=', $judge->id);

                $rankedScore = $category->ranked_scores
                    ->where('judge_id', '=', $judge->id)
                    ->where('contestant_id', '=', $contestant->id)
                    ->first();
              @endphp

              <td class="px-2 py-1 text-center align-middle border border-black">
                <div>{{ round($judgeScore['points'] ?? 0, 4) }}</div>
                <div>Rank {{ $rankedScore['rank'] ?? 0 }}</div>
              </td>
            @endforeach

            <th class="px-2 py-1 align-middle border border-black">
              <div>{{ $categoryContestant->ranking }}</div>
              <div>Sum of Rank: {{ $categoryContestant->rank_sum }}</div>
            </th>
          @endforeach

          <th class="px-2 py-1 align-middle border border-black">{{ $contestant->rank_sum }}</th>
        </tr>
      @endif

      @if ($contest->scoring_system == 'average')
        <tr style="page-break-before: avoid;"
          class="{{ !$loop->last ? 'border-b-2 border-black' : '' }}">
          <th class="px-2 py-1 text-right align-top border border-black">Average:</th>

          @foreach ($contest->categories as $category)
            <th class="px-2 py-1 text-center align-top border border-black"
              colspan="{{ $category->judges()->count() }}">
              @php
                $categoryScore = $contestant->category_scores->firstWhere('id', '=', $category->id);
              @endphp

              {{ round($categoryScore->average, 4) }}
            </th>
          @endforeach
        </tr>
      @endif
    @endforeach
  </tbody>
</table>
<br />
<br />
<h2 class="text-xs font-bold"
  style="page-break-before: avoid;">Breakdown</h2>
<br />
@foreach ($contest->categories as $category)
  <div style="page-break-before: {{ $loop->first ? 'auto' : 'always' }};">
    @include('admin.scores.category')
  </div>
  <br />
@endforeach
