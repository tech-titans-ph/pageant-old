@extends('layouts.admin')
@section('content')
	@breadcrumb([
		'links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests'],
			['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Scores']), 'title' => $contest->name],
		],
		'class' => 'mb-10'
	])
		@pageHeader() Contestant Score @endpageHeader
	@endbreadcrumb
	<div class="flex">
		<div class="w-3/4">
			@card()
				<div class="flex mb-4 mx-auto">
					<img src="{{ Storage::url($contestant->picture) }}" class="flex-none object-cover object-center w-32 h-32 rounded-full border">
					<div class="flex-grow self-center px-4">
						<div class="mb-2 font-bold">#{{ $contestant->number }} - {{ $contestant->name }}</div>
						<div class="italic">{{ $contestant->description }}</div>
					</div>
					<div class="flex-none self-center text-green-700 text-6xl font-bold">
						{{ round($totalPercentage, 4) }}
					</div>
				</div>
				<table class="border w-full">
					<thead>
						<tr>
							<th class="text-gray-700 p-2 text-left">Judges</th>
							@foreach($contest->categories as $category)
								<th class="text-gray-700 p-2 align-bottom">{{ $category->name }}<br>{{ $category->percentage }}%</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($contest->judges as $judge)
							<tr class="border-t text-gray-700 font-medium">
								<td class="text-gray-700 font-medium p-2">
									{{ $judge->user->name }}
								</td>
								@php
									$total = 0;
								@endphp
								@foreach($contest->categories as $category)
									<td class="whitespace-no-wrap text-center p-2 text-3xl">
										<div class="text-3xl">
											@php
												$score = 0;

												$categoryJudge = $category->categoryJudges()->where('judge_id', $judge->id)->first();
												$categoryContestant = $category->categoryContestants()->where('contestant_id', $contestant->id)->first();
												
												if($categoryJudge && $categoryContestant){
													$score = $category->categoryScores()
														->where([
															['category_judge_id', '=', $categoryJudge->id],
															['category_contestant_id', '=', $categoryContestant->id],
														])
														->first()->criteriaScores()->sum('score');

													$score = ($score / $category->criterias()->sum('percentage')) * $category->percentage;
												}

												$total += $score;
											@endphp
											{{ round($score, 4) }}
										</div>
									</td>
								@endforeach
							</tr>
						@endforeach
					</tbody>
					<tfoot class="text-gray-700 font-bold border-t"><tr>
						<th class="text-left py-2 px-4">Average</th>
						@foreach($categoryContestants as $categoryContestant)
							<th class="text-center py-2 px-4 text-3xl">
								<a href="{{ route('admin.contests.categories.category-contestants.show', ['contest' => $contest->id, 'category' => $categoryContestant->category->id, 'categoryContestant' => $categoryContestant->id]) }}">
									{{ round($categoryContestant->averagePercentage, 4) }}
								</a>
							</th>
						@endforeach
					</tr></tfoot>
				</table>
			@endcard
		</div>
	</div>
@endsection