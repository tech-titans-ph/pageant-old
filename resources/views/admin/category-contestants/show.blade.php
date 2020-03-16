@extends('layouts.admin')
@section('content')
	@breadcrumb([
		'links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests'],
			['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Categories']), 'title' => $contest->name],
			['url' => route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Scores']), 'title' => $category->name],
		],
		'class' => 'mb-10',
	])
		@pageHeader() Contestant Score @endpageHeader
	@endbreadcrumb
	
	<div class="flex">
		<div class="w-3/4">
			@card()
				<div class="flex mb-4">
					<a href="{{ route('admin.contests.contestants.show', ['contest' => $contest->id, 'contestant' => $categoryContestant->contestant->id]) }}" >
						<img src="{{ Storage::url($categoryContestant->contestant->picture) }}" class="flex-none object-cover object-center w-32 h-32 rounded-full border">
					</a>
					<div class="flex-grow self-center px-4">
						<div class="font-bold">#{{ $categoryContestant->contestant->number }} - {{ $categoryContestant->contestant->name }}</div>
						<div class="mt-2 italic">{{ $categoryContestant->contestant->description }}</div>
						<div class="mt-4 font-bold">{{ $category->name . ' - ' . $category->percentage }}%</div>
					</div>
					<div class="flex-none self-center whitespace-no-wrap text-green-700 text-5xl font-bold">
						{{ round($averagePercentage, 4) }}%
					</div>
				</div>
				<table class="border w-full">
					<thead><tr>
						<th class="text-gray-700 p-2 text-left">Judges</th>
						@foreach($category->criterias as $criteria)
							<th class="text-gray-700 p-2 align-bottom">{{ $criteria->name }}<br>({{ $criteria->percentage }})</th>
						@endforeach
						<th class="text-gray-700 p-2">Total</th>
						<th class="text-gray-700 p-2">Percentage</th>
					</tr></thead>
					<tbody>
						@foreach($category->categoryJudges()->orderBy('judge_id')->get() as $categoryJudge)
							<tr class="border-t">
								<td class="text-gray-700 font-medium p-2">
									{{ $categoryJudge->judge->user->name }}
								</td>
								@foreach($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->get() as $criteriaScore)
									<td class="text-gray-700 font-medium text-3xl text-center p-2">
										{{ $criteriaScore->score }}
									</td>
								@endforeach
								<td class="text-gray-700 font-semibold text-4xl text-center py-2 px-2">
									{{ $categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') }}
								</td>
								<td class="text-gray-700 font-bold text-5xl text-right py-2 px-2">
									{{
										round(
											($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') / $category->criterias()->sum('percentage')) * $category->percentage,
											4
										) 
									}}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@endcard
		</div>
	</div>
@endsection