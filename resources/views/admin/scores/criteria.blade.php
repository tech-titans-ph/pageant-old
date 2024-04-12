{{-- @props(['criteria', 'category']) --}}

<div>
  <div>
    <div class="font-semibold">{{ $criteria->name }}</div>
    <div>{{ $criteria->max_points_percentage }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
  </div>

  <table class="w-full mt-4">
    <thead>
      <tr>
        <th class="px-2 py-1 border"
          colspan="2">{{ Str::plural('Contestant', $criteria->ranked_contestants->count()) }}</th>
        <th class="px-2 py-1 border">Judges</th>

        <th class="px-2 py-1 border">
          <div>{{ $criteria->name }}</div>
          <div>{{ $criteria->max_points_percentage }} {{ $category->scoring_system == 'average' ? '%' : 'points' }}</div>
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach ($criteria->ranked_contestants as $contestant)
        @foreach ($category->judges as $judgeKey => $judge)
          @php
            $judgeScore = $contestant->judge_scores[$judge->pivot->id] ?? null;
          @endphp

          <tr style="{{ $judgeKey ? 'page-break-before: avoid' : 'page-break-inside: avoid' }};"
            class="{{ $loop->parent->first ? 'bg-green-100' : '' }}">
            @if (!$judgeKey)
              <td class="w-40 px-2 py-1 text-center align-top border whitespace-nowrap {{ $loop->parent->first ? 'font-bold text-lg' : '' }}"
                rowspan="{{ $category->judges->count() }}">
                <img src="{{ $contestant->avatar_url }}"
                  class="object-cover object-center w-32 h-32 mx-auto mb-1 border rounded-full" />
                <div>Top {{ $contestant->ranking }}</div>
              </td>
              <td class="px-2 py-1 align-top border whitespace-nowrap {{ $loop->parent->first ? 'font-bold text-lg' : '' }}"
                rowspan="{{ $category->judges->count() }}">
                <div>#{{ $contestant->order }} - {{ $contestant->name }}</div>
                <div>{{ $contestant->alias }}</div>
              </td>
            @endif

            <td class="px-2 py-1 border whitespace-nowrap">{{ $judge->name }}</td>

            @php
              $criteriaScore = $judgeScore ? $judgeScore['scores']->first() : null;
            @endphp

            <td class="px-2 py-1 text-center border whitespace-nowrap">
              <div>{{ $criteriaScore['points'] ?? 0 }}</div>
            </td>
          </tr>
        @endforeach

        @if ($category->scoring_system == 'average')
          <tr style="page-break-before: avoid;">
            <th class="px-2 py-1 text-right border"
              colspan="3">
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

            <th class="px-2 py-1 text-center border">
              {{ $contestant->ranking }}
            </th>
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
