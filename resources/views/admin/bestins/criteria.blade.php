@if ($criteria->max_points_percentage)
  <div class="font-bold">{{ $criteria->max_points_percentage }} {{ $contest->scoring_system == 'average' ? '%' : 'points' }}</div>
@endif

<table class="w-full mt-4">
  <thead>
    <tr>
      <th class="px-2 py-1 border"
        colspan="2">{{ Str::plural('Contestant', $criteria->ranked_contestants->count()) }}</th>

      @foreach ($category->judges as $judge)
        <th class="px-2 py-1 border">{{ $judge->name }}</th>
      @endforeach

      <th class="px-2 py-1 border">Average</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($criteria->ranked_contestants as $contestant)
      <tr class="{{ $loop->first ? 'bg-green-100' : '' }}"
        style="page-break-inside: avoid;">
        <td class="px-2 py-1 text-center align-middle border whitespace-no-wrap {{ $loop->first ? 'font-bold text-xs' : '' }}">
          <div>Top {{ $contestant->ranking }}</div>
        </td>
        <td class="px-2 py-1 text-left border {{ $loop->first ? 'font-bold text-xs' : '' }}">
          <div># {{ $contestant->order }} - {{ $contestant->name }}</div>
          <div>{{ $contestant->alias }}</div>
        </td>

        @foreach ($category->judges as $judgeKey => $judge)
          @php
            $judgeScore = $contestant->judge_scores[$judge->pivot->id] ?? null;

            $criteriaScore = $judgeScore ? $judgeScore['scores']->first() : null;
          @endphp

          <td class="px-2 py-1 text-center border">{{ $criteriaScore['points'] ?? 0, 4 }}</td>
        @endforeach

        <td class="px-2 py-1 text-center border">{{ round($contestant->average, 4) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
