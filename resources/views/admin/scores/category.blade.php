{{-- @props(['category', 'contest']) --}}

<div>
  <div>
    <div class="font-semibold">{{ $category->name }}</div>

    @if ($category->max_points_percentage)
      <div>{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
    @endif
  </div>

  <table class="w-full mt-4">
    <thead>
      <tr>
        <th class="px-2 py-1 border"
          colspan="2">Contestants</th>
        <th class="px-2 py-1 border">Judges</th>

        @if ($category->has_criterias)
          @foreach ($category->criterias as $criteria)
            <th class="px-2 py-1 border">
              <div>{{ $criteria->name }}</div>
              <div>{{ $criteria->max_points_percentage }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
            </th>
          @endforeach

          @if ($category->has_criterias && $category->scoring_system == 'average')
            <th class="px-2 py-1 border whitespace-nowrap">
              <div>Total</div>
              <div>{{ $category->criterias->sum('max_points_percentage') }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
            </th>

            @if ($contest->scoring_system == 'average')
              <th class="px-2 py-1 border whitespace-nowrap">
                <div>Category</div>
                <div>{{ $category->max_points_percentage }} %</div>
              </th>
            @endif
          @endif
        @else
          <th class="px-2 py-1 border">
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

          <tr>
            @if (!$judgeKey)
              <td class="px-2 py-1 align-top border whitespace-nowrap"
                rowspan="{{ $category->judges->count() }}">Top {{ $contestant->ranking }}</td>
              <td class="px-2 py-1 align-top border whitespace-nowrap"
                rowspan="{{ $category->judges->count() }}">
                <div>#{{ $contestant->order }} - {{ $contestant->name }}</div>
                <div>{{ $contestant->alias }}</div>
              </td>
            @endif

            <td class="px-2 py-1 border whitespace-nowrap">{{ $judge->name }}</td>

            @if ($category->has_criterias)
              @foreach ($category->criterias as $criteria)
                @php
                  $criteriaScore = $judgeScore ? $judgeScore['scores']->firstWhere('criteria_id', '=', $criteria->id) : null;
                @endphp

                <td class="px-2 py-1 text-center border whitespace-nowrap">
                  <div>{{ $criteriaScore['points'] ?? 0 }}</div>
                </td>
              @endforeach

              @if ($category->scoring_system == 'average')
                <td class="px-2 py-1 text-center border whitespace-nowrap">
                  {{ $judgeScore['points_sum'] ?? 0 }}
                </td>

                @if ($contest->scoring_system == 'average')
                  <td class="px-2 py-1 text-center border whitespace-nowrap">
                    {{ round($judgeScore['points_percentage'] ?? 0, 4) }}
                  </td>
                @endif
              @endif
            @else
              @php
                $categoryScore = $judgeScore ? $judgeScore['scores']->firstWhere('category_id', '=', $category->id) : null;
              @endphp

              <td class="px-2 py-1 text-center border whitespace-nowrap">
                <div>{{ $categoryScore['points'] ?? 0 }} </div>
              </td>
            @endif
          </tr>
        @endforeach

        @if (($category->has_criterias && $category->scoring_system == 'average') || (!$category->has_criterias && $contest->scoring_system == 'average'))
          <tr>
            <th class="px-2 py-1 text-right border"
              colspan="{{ 4 + ($category->criterias->count() ?: -1) - ($contest->scoring_system == 'ranking' ? 1 : 0) }}">
              Average:
            </th>
            <th class="px-2 py-1 text-center border">
              {{ round($contestant->average, 4) }}
            </th>
          </tr>
        @else
          <tr>
            <th class="px-2 py-1 text-right border"
              colspan="3">
              Ranking:
            </th>

            @forelse($category->criterias as $criteria)
              <th class="px-2 py-1 text-center border">
                {{ $contestant->ranks->firstWhere('group_id', '=', $criteria->id)['rank'] }}
              </th>
            @empty
              <th class="px-2 py-1 text-center border">
                {{ $contestant->ranking }}
              </th>
            @endforelse
          </tr>
        @endif

        <tr>
          <td>&nbsp;</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
