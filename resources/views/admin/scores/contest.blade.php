{{-- @props(['contest']) --}}

<div>
  <h2>Summary of Scores</h2>

  <table class="w-full mt-4">
    <thead>
      <th class="px-2 py-1 border"
        colspan="{{ $contest->scoring_system == 'average' ? 2 : 3 }}">Contestants</th>

      @if ($contest->scoring_system == 'average')
        <th class="px-2 py-1 border">Judges</th>
      @endif

      @foreach ($contest->categories as $category)
        <th class="px-2 py-1 border">
          <div>{{ $category->name }}</div>

          @if ($category->max_points_percentage)
            <div>{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
          @endif
        </th>
      @endforeach

      @if ($contest->scoring_system == 'average')
        <th class="px-2 py-1 text-center border">Total</th>
      @endif
    </thead>
    <tbody>
      @foreach ($contest->ranked_contestants as $contestant)
        @if ($contest->scoring_system == 'average')
          @foreach ($contest->judges as $judgeKey => $judge)
            <tr>
              @if (!$judgeKey)
                <td class="px-2 py-1 align-top border"
                  rowspan="{{ $contest->judges->count() }}">Top n</td>
                <td class="px-2 py-1 align-top border"
                  rowspan="{{ $contest->judges->count() }}">
                  <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
                  <div>{{ $contestant->alias }}</div>
                </td>
              @endif

              <td class="px-2 py-1 align-top border">{{ $judge->name }}</td>

              @foreach ($contest->categories as $category)
                <td class="px-2 py-1 text-center align-top border">
                  @php
                    $categoryScore = $contestant->category_scores->firstWhere('id', '=', $category->id);

                    $judgeScore = $categoryScore->judge_scores->firstWhere('judge_id', '=', $judge->id);
                  @endphp

                  {{ round($judgeScore['points_percentage'], 4) }}
                </td>
              @endforeach

              @if (!$judgeKey && $contest->scoring_system == 'average')
                <td class="px-2 py-1 align-top border"
                  rowspan="{{ $contest->judges->count() }}">&nbsp;</td>
              @endif
            </tr>
          @endforeach
        @endif

        @if ($contest->scoring_system == 'ranking')
          <tr>
            <td class="px-2 py-1 align-top border">Top {{ $contestant->ranking }}</td>
            <td class="px-2 py-1 align-top border">
              <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
              <div>{{ $contestant->alias }}</div>
            </td>
            <td class="px-2 py-1 text-right align-middle border">Ranking:</td>

            @foreach ($contest->categories as $category)
              <td class="px-2 py-1 text-center align-middle border">
                {{ $contestant->ranks->firstWhere('category_id', '=', $category->id)['ranking'] }}
              </td>
            @endforeach
          </tr>
        @endif

        @if ($contest->scoring_system == 'average')
          <tr>
            <th class="px-2 py-1 text-right align-top border"
              colspan="3">Average :</th>


            @foreach ($contest->categories as $category)
              <th class="px-2 py-1 text-center align-top border">
                @php
                  $categoryScore = $contestant->category_scores->firstWhere('id', '=', $category->id);
                @endphp

                {{ round($categoryScore->average, 4) }}
              </th>
            @endforeach

            @if ($contest->scoring_system == 'average')
              <th class="px-2 py-1 text-center align-top border ">{{ round($contestant->average_sum, 4) }}</th>
            @endif
          </tr>

          <tr>
            <td>&nbsp;</td>
          </tr>
        @endif
      @endforeach

    </tbody>
  </table>

  <h2 class="mt-8">Breakdown</h2>

  <div class="mt-4 space-y-8">
    @foreach ($contest->categories as $category)
      @include('admin.scores.category')
    @endforeach
  </div>
</div>
