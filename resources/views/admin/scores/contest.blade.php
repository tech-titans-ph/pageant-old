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
            <tr style="{{ $judgeKey ? 'page-break-before: avoid' : 'page-break-inside: avoid' }};"
              class="{{ $loop->parent->first ? 'bg-green-100' : '' }}">
              @if (!$judgeKey)
                <td class="w-40 px-2 py-1 text-center align-top border whitespace-nowrap {{ $loop->parent->first ? 'font-bold text-lg' : '' }}"
                  rowspan="{{ $contest->judges->count() }}">
                  <img src="{{ $contestant->avatar_url }}"
                    class="object-cover object-center w-32 h-32 mx-auto mb-1 border rounded-full" />
                  <div>Top {{ $contestant->ranking }}</div>
                </td>
                <td class="px-2 py-1 align-top border {{ $loop->parent->first ? 'font-bold text-lg' : '' }}"
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
          <tr class="{{ $loop->first ? 'bg-green-100' : '' }}">
            <td class="w-40 px-2 py-1 text-center align-top border whitespace-nowrap {{ $loop->first ? 'font-bold text-lg' : '' }}">
              <img src="{{ $contestant->avatar_url }}"
                class="object-cover object-center w-32 h-32 mx-auto mb-1 border rounded-full" />
              <div>Top {{ $contestant->ranking }}</div>
            </td>
            <td class="px-2 py-1 align-top border {{ $loop->first ? 'font-bold text-lg' : '' }}">
              <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
              <div>{{ $contestant->alias }}</div>
            </td>
            <td class="px-2 py-1 text-right align-middle border">Ranking:</td>

            @foreach ($contest->categories as $category)
              <td class="px-2 py-1 text-center align-middle border">
                {{ $contestant->ranks->firstWhere('category_id', '=', $category->id)['rank'] }}
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
