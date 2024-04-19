{{-- @props(['contest']) --}}

<div>
  <h2>Summary of Scores</h2>

  <table class="w-full mt-4">
    <thead>
      <tr>
        <th class="px-2 py-1 border"
          colspan="{{ $contest->scoring_system == 'average' ? 1 : 3 }}"
          rowspan="2">Contestants</th>
        @foreach ($contest->categories as $category)
          <th class="px-2 py-1 border"
            colspan="{{ $category->judges->count() }}">
            <div>{{ $category->name }}</div>
            @if ($category->max_points_percentage)
              <div>{{ $category->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
            @endif
          </th>
        @endforeach

        @if ($contest->scoring_system == 'average')
          <th class="px-2 py-1 text-center border"
            rowspan="2">TOTAL</th>
        @endif
      </tr>
      <tr>
        @foreach ($contest->categories as $category)
          @foreach ($category->judges as $judge)
            <th class="px-2 py-1 border">
              {!! str_replace(' ', '<br />', $judge->name) !!}
            </th>
          @endforeach
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach ($contest->ranked_contestants as $contestantIndex => $contestant)
        @if ($contest->scoring_system == 'average')
          <tr style="{{ $contestantIndex ? 'page-break-before: avoid' : 'page-break-inside: avoid' }};"
            class="{{ $loop->first ? 'bg-green-100' : '' }}">
            @if (Str::endsWith(Route::currentRouteName(), '.print'))
              <td class="px-2 py-1 text-center align-top border {{ $loop->first ? 'font-bold text-sm' : '' }}">
                <div>Top {{ $contestant->ranking }}</div>
                <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
                <div>{{ $contestant->alias }}</div>
              </td>
            @else
              <td class="w-40 px-2 py-1 text-center align-top border whitespace-nowrap {{ $loop->first ? 'font-bold text-sm' : '' }}">
                <img src="{{ $contestant->avatar_url }}"
                  class="object-cover object-center w-24 h-24 mx-auto mb-1 border rounded-full" />
                <div>Top {{ $contestant->ranking }}</div>
              </td>
              <td class="px-2 py-1 align-top border {{ $loop->first ? 'font-bold text-sm' : '' }}">
                <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
                <div>{{ $contestant->alias }}</div>
              </td>
            @endif

            @foreach ($contest->categories as $category)
              @foreach ($category->judges as $judge)
                <td class="px-2 py-1 text-center align-middle border">
                  @php
                    $categoryScore = $contestant->category_scores->firstWhere('id', '=', $category->id);

                    $judgeScore = $categoryScore->judge_scores->firstWhere('judge_id', '=', $judge->id);
                  @endphp

                  {{ round($judgeScore['points_percentage'] ?? 0, 4) }}
                </td>
              @endforeach
            @endforeach

            @if ($contest->scoring_system == 'average')
              <th class="px-2 py-1 align-bottom border"
                rowspan="2">{{ round($contestant->average_sum, 4) }}</th>
            @endif
          </tr>
        @endif

        @if ($contest->scoring_system == 'ranking')
          <tr class="{{ $loop->first ? 'bg-green-100' : '' }}">
            @if (Str::endsWith(Route::currentRouteName(), '.print'))
              <td class="px-2 py-1 text-center align-top border whitespace-nowrap {{ $loop->first ? 'font-bold text-sm' : '' }}"
                colspan="2">
                <div>Top {{ $contestant->ranking }}</div>
                <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
                <div>{{ $contestant->alias }}</div>
              </td>
            @else
              <td class="w-40 px-2 py-1 text-center align-top border whitespace-nowrap {{ $loop->first ? 'font-bold text-sm' : '' }}">
                <img src="{{ $contestant->avatar_url }}"
                  class="object-cover object-center w-24 h-24 mx-auto mb-1 border rounded-full" />
                <div>Top {{ $contestant->ranking }}</div>
              </td>
              <td class="px-2 py-1 align-top border {{ $loop->first ? 'font-bold text-sm' : '' }}">
                <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
                <div>{{ $contestant->alias }}</div>
              </td>
            @endif
            <td class="px-2 py-1 text-right align-middle border">Ranking:</td>

            @foreach ($contest->categories as $category)
              <td class="px-2 py-1 text-center align-middle border">
                {{ $contestant->ranks->firstWhere('category_id', '=', $category->id)['rank'] }}
              </td>
            @endforeach
          </tr>
        @endif

        @if ($contest->scoring_system == 'average')
          <tr style="page-break-before: avoid;">
            <th class="px-2 py-1 text-right align-top border">Average:</th>

            @foreach ($contest->categories as $category)
              <th class="px-2 py-1 text-center align-top border"
                colspan="{{ $category->judges()->count() }}">
                @php
                  $categoryScore = $contestant->category_scores->firstWhere('id', '=', $category->id);
                @endphp

                {{ round($categoryScore->average, 4) }}
              </th>
            @endforeach
          </tr>

          @if (!$loop->last)
            <tr>
              <td>&nbsp;</td>
            </tr>
          @endif
        @endif
      @endforeach

    </tbody>
  </table>

  <h2 class="mt-8"
    style="page-break-before: always;">Breakdown</h2>

  <div class="mt-4 space-y-8">
    @foreach ($contest->categories as $category)
      <div style="page-break-before: {{ $loop->first ? 'auto' : 'always' }};">
        @include('admin.scores.category')
      </div>
    @endforeach
  </div>
</div>
