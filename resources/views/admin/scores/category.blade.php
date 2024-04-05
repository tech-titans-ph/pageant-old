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
          colspan="2">{{ Str::plural('Contestant', $category->ranked_contestants->count()) }}</th>
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

          <tr style="{{ $judgeKey ? 'page-break-before: avoid' : 'page-break-inside: avoid' }};">
            @if (!$judgeKey)
              <td class="w-40 px-2 py-1 text-center align-top border whitespace-nowrap"
                rowspan="{{ $category->judges->count() }}">
                <img src="{{ $contestant->avatar_url }}"
                  class="object-cover object-center w-32 h-32 mx-auto mb-1 border rounded-full" />
                <div>Top {{ $contestant->ranking }}</div>
              </td>
              <td class="px-2 py-1 align-top border whitespace-nowrap"
                rowspan="{{ $category->judges->count() }}">
                <div>#{{ $contestant->pivot->order }} - {{ $contestant->name }}</div>
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
          <tr style="page-break-before: avoid;">
            <th class="px-2 py-1 text-right border"
              colspan="{{ 4 + ($category->criterias->count() ?: -1) - ($contest->scoring_system == 'ranking' ? 1 : 0) }}">
              Average:
            </th>
            <th class="px-2 py-1 text-center border">
              {{ round($contestant->average, 4) }}
            </th>
          </tr>
        @else
          <tr style="page-break-before: avoid;">
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

        @if (!$loop->last)
          <tr>
            <td>&nbsp;</td>
          </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>
