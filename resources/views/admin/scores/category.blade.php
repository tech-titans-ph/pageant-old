{{-- @props(['category', 'contest']) --}}

<div>
  <div>
    <div class="text-xs font-bold">{{ $category->name }}</div>

    @if ($category->max_points_percentage)
      <div class="text-xs font-bold">{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
    @endif
  </div>
  <br />
  <table class="w-full">
    <thead>
      <tr>
        <th class="px-2 py-1 border border-black"
          colspan="2">{{ Str::plural('Contestant', $category->ranked_contestants->count()) }}</th>
        <th class="px-2 py-1 border border-black">Judges</th>

        @if ($category->has_criterias)
          @foreach ($category->criterias as $criteria)
            <th class="px-2 py-1 border border-black">
              <div>{{ $criteria->name }}</div>
              <div>{{ $criteria->max_points_percentage }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
            </th>
          @endforeach

          @if ($category->has_criterias && $category->scoring_system == 'average')
            <th class="px-2 py-1 border border-black whitespace-nowrap">
              <div>Total</div>
              <div>{{ $category->criterias->sum('max_points_percentage') }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
            </th>

            @if ($contest->scoring_system == 'average')
              <th class="px-2 py-1 border border-black whitespace-nowrap">
                <div>Category</div>
                <div>{{ $category->max_points_percentage }} %</div>
              </th>
            @endif

            @if ($contest->scoring_system == 'ranking')
              <th class="px-2 py-1 border border-black whitespace-nowrap">Ranking</th>
            @endif
          @endif
        @else
          <th class="px-2 py-1 border border-black">
            <div>{{ $category->name }}</div>
            <div>{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
          </th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($category->ranked_contestants as $contestant)
        @foreach ($category->judges as $judgeKey => $judge)
          @php
            $judgeScore = $contestant->judge_scores[$judge->pivot->id] ?? null;
          @endphp

          <tr style="{{ $judgeKey ? 'page-break-before: avoid' : 'page-break-inside: avoid' }};"
            class="{{ $loop->parent->first ? 'bg-green-100' : '' }}">
            @if (!$judgeKey)
              @if (Str::endsWith(Route::currentRouteName(), '.print'))
                <td class="px-2 py-1 text-center align-top border border-black whitespace-nowrap {{ $loop->parent->first ? 'font-bold text-xs' : '' }}"
                  colspan="2"
                  rowspan="{{ $category->judges->count() }}">
                  <div>Top {{ $contestant->ranking }}</div>
                  <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
                  <div>{{ $contestant->alias }}</div>
                </td>
              @else
                <td class="w-40 px-2 py-1 text-center align-top border border-black whitespace-nowrap {{ $loop->parent->first ? 'font-bold text-xs' : '' }}"
                  rowspan="{{ $category->judges->count() }}">
                  <img src="{{ $contestant->avatar_url }}"
                    class="object-cover object-center w-24 h-24 mx-auto mb-1 border border-black rounded-full" />
                  <div>Top {{ $contestant->ranking }}</div>
                </td>
                <td class="px-2 py-1 align-top border border-black whitespace-nowrap {{ $loop->parent->first ? 'font-bold text-xs' : '' }}"
                  rowspan="{{ $category->judges->count() }}">
                  <div>#{{ $contestant->order }} - {{ $contestant->name }}</div>
                  <div>{{ $contestant->alias }}</div>
                </td>
              @endif
            @endif

            <td class="px-2 py-1 border border-black whitespace-nowrap">{{ $judge->name }}</td>

            @if ($category->has_criterias)
              @foreach ($category->criterias as $criteria)
                @php
                  $criteriaScore = $judgeScore ? $judgeScore['scores']->firstWhere('criteria_id', '=', $criteria->id) : null;
                @endphp

                <td class="px-2 py-1 text-center border border-black whitespace-nowrap">
                  <div>{{ $criteriaScore['points'] ?? 0 }}</div>
                </td>
              @endforeach

              @if ($category->scoring_system == 'average')
                <td class="px-2 py-1 text-center border border-black whitespace-nowrap">
                  {{ $judgeScore['points_sum'] ?? 0 }}
                </td>

                @if ($contest->scoring_system == 'average')
                  <td class="px-2 py-1 text-center border border-black whitespace-nowrap">
                    {{ round($judgeScore['points_percentage'] ?? 0, 4) }}
                  </td>
                @endif
              @endif
            @else
              @php
                $categoryScore = $judgeScore ? $judgeScore['scores']->firstWhere('category_id', '=', $category->id) : null;
              @endphp

              <td class="px-2 py-1 text-center border border-black whitespace-nowrap">
                <div>{{ $categoryScore['points'] ?? 0 }} </div>
              </td>
            @endif

            @if ($contest->scoring_system == 'ranking' && $category->scoring_system == 'average')
              @php
                $rankedScore = $category->ranked_scores
                    ->where('contestant_id', '=', $contestant->id)
                    ->where('judge_id', '=', $judge->id)
                    ->first();
              @endphp

              <th class="px-2 py-1 border border-black">{{ $rankedScore['rank'] ?? 0 }}</th>
            @endif
          </tr>
        @endforeach

        @if ($contest->scoring_system == 'average' && $category->scoring_system == 'average')
          <tr style="page-break-before: avoid;"
            class="{{ !$loop->last ? 'border-b-2 border-black' : '' }}">
            <th class="px-2 py-1 text-right border border-black"
              colspan="{{ 4 + ($category->criterias->count() ?: -1) - ($contest->scoring_system == 'ranking' ? 1 : 0) }}">
              Average:
            </th>
            <th class="px-2 py-1 text-center border border-black">
              {{ round($contestant->average, 4) }}
            </th>
          </tr>
        @elseif ($contest->scoring_system == 'ranking' && $category->scoring_system == 'ranking')
          <tr style="page-break-before: avoid;"
            class="{{ !$llop->last ? 'border-b-2 border-black' : '' }}">
            <th class="px-2 py-1 text-right border border-black"
              colspan="3">
              Ranking:
            </th>

            @forelse($category->criterias as $criteria)
              <th class="px-2 py-1 text-center border border-black">
                {{ $contestant->ranks->firstWhere('group_id', '=', $criteria->id)['rank'] ?? '' }}
              </th>
            @empty
              <th class="px-2 py-1 text-center border border-black">
                {{ $contestant->ranking }}
              </th>
            @endforelse
          </tr>
        @elseif($contest->scoring_system == 'ranking' && $category->scoring_system == 'average')
          <tr class="{{ !$loop->last ? 'border-b-2 border-black' : '' }}">
            <th class="px-2 py-1 text-right border border-black"
              colspan="{{ $category->criterias->count() + 4 }}">Sum of Rank:</th>
            <th class="px-2 py-1 border border-black">{{ $contestant->rank_sum }}</th>
          </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
