@extends('layouts.admin')
@section('content')
  @breadcrumb([
      'links' => [
          ['url' => route('admin.contests.index'), 'title' => 'Contests'],
          ['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories']), 'title' => $contest->name],
          ['url' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Scores']), 'title' => $category->name]
      ],
      'class' => 'mb-10'
  ])
    @pageHeader()
      Contestant Score
    @endpageHeader
  @endbreadcrumb

  <div class="flex">
    <div class="w-3/4">
      @card()
        <div class="flex mb-4">
          <a href="{{ route('admin.contests.contestants.show', ['contest' => $contest->id, 'contestant' => $categoryContestant->contestant->id]) }}">
            <img src="{{ Storage::url($categoryContestant->contestant->picture) }}"
              class="flex-none object-cover object-center w-32 h-32 border rounded-full">
          </a>
          <div class="self-center flex-grow px-4">
            <div class="font-bold">#{{ $categoryContestant->contestant->number }} - {{ $categoryContestant->contestant->name }}</div>
            <div class="mt-2 italic">{{ $categoryContestant->contestant->description }}</div>
            <div class="mt-4 font-bold">{{ $category->name . ' - ' . $category->percentage }} points</div>
          </div>
          {{-- <div class="self-center flex-none text-5xl font-bold text-green-700 whitespace-no-wrap">
            {{ round($averagePercentage, 4) }}%
            {{ number_format($total) }}
          </div> --}}
        </div>
        <table class="w-full border">
          <thead>
            <tr>
              <th class="p-2 text-left text-gray-700">Judges</th>
              @foreach ($category->criterias as $criteria)
                <th class="p-2 text-gray-700 align-bottom">{{ $criteria->name }}<br>({{ $criteria->percentage }} points)</th>
              @endforeach
              {{-- <th class="p-2 text-gray-700">Total</th> --}}
              {{-- <th class="p-2 text-gray-700">Percentage</th> --}}
            </tr>
          </thead>
          <tbody>
            @foreach ($category->categoryJudges()->orderBy('judge_id')->get() as $categoryJudge)
              <tr class="border-t">
                <td class="p-2 font-medium text-gray-700">
                  {{ $categoryJudge->judge->user->name }}
                </td>
                @foreach ($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->get() as $criteriaScore)
                  <td class="p-2 text-3xl font-medium text-center text-gray-700">
                    {{ $criteriaScore->score }}
                  </td>
                @endforeach
                {{-- <td class="px-2 py-2 text-4xl font-semibold text-center text-gray-700">
                  {{ $categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') }}
                </td> --}}
                {{-- <td class="px-2 py-2 text-5xl font-bold text-right text-gray-700">
									{{
										round(
											($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') / $category->criterias()->sum('percentage')) * $category->percentage,
											4
										)
									}}
								</td> --}}
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endcard
  </div>
  </div>
@endsection
