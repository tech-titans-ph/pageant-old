<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>{{ config('app.name') }}</title>
	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="p-4 text-sm text-gray-700">
	<div id="app">
		<div class="flex w-1/3 mx-auto mb-4">
			<div class="flex-none">
				<img src="{{ Storage::url($contest->logo) }}" class="object-contain object-center w-32 h-32 border rounded">
			</div>
			<div class="self-center flex-shrink px-4">
				<div class="text-2xl font-medium">{{ $contest->name }}</div>
			</div>
		</div>
		<div class="px-1 mb-4">
			<p class="mb-4 text-2xl font-bold">Category Scores</p>
			<div class="p-2 mb-4 border-t">
				<p class="mb-4 text-xl font-bold">{{ $category->name }} - {{ $category->percentage }} points</p>
				<table class="w-full">
					<thead>
						<tr>
							<th class="p-1 border" colspan="2">Contestants</th>
							<th class="p-1 border">Judges</th>
							@foreach($category->criterias as $criteria)
								<th class="p-1 border">{{ $criteria->name }}<br>{{ $criteria->percentage }} points</th>
							@endforeach
							<th class="p-1 border">Total</th>
							{{-- <th class="p-1 border">Percentage</th> --}}
						</tr>
					</thead>
					<tbody>
						@php
							$top = 0;
						@endphp
						@foreach($scoredCategoryContestants as $categoryContestant)
							@php
								$top++;
							@endphp
							@foreach($category->categoryJudges()->orderBy('judge_id')->get() as $key => $categoryJudge)
								<tr class="">
									@if(!$key)
										<td class="p-1 text-center align-top border" rowspan="{{ $category->categoryJudges()->count() }}">Top {{ $top }}</td>
										<td class="w-auto p-1 align-top border" rowspan="{{ $category->categoryJudges()->count() }}">
											<span class="font-medium">#{{ $categoryContestant->contestant->number }} - {{ $categoryContestant->contestant->name }}</span>
											<br>
											<span class="italic">{{ $categoryContestant->contestant->description }}</span>
										</td>
									@endif
									<td class="p-1 border">{{ $categoryJudge->judge->user->name }}</td>
									@foreach($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->get() as $criteriaScore)
										<td class="p-1 text-center border">&nbsp;{{ $criteriaScore->score }}</td>
									@endforeach
									<td class="p-1 font-medium text-center border">{{ $categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') }}</td>
									{{-- <td class="p-1 font-medium text-center border">
										{{
											round(
												($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') / $category->criterias()->sum('percentage')) * $category->percentage,
												4
											)
										}}
									</td> --}}
								</tr>
							@endforeach
							{{-- <tr>
								<td class="p-1 font-bold text-right border" colspan="{{ $category->criterias()->count() + 4 }}">Average:</td>
								<td class="p-1 font-bold text-center border">{{ round($categoryContestant->averagePercentage, 4) }}</td>
							</tr> --}}
							<tr><td class="p-1 border" colspan="{{ $category->criterias()->count() + 5 }}">&nbsp;</td></tr>
						@endforeach
					</tbody>
				</table>
				<div class="w-64 mt-10 ml-auto mr-10 text-center">
					Approved By
					<div class="pt-2 mt-10 font-bold border-t-2">
						CHAIRMAN OF THE BOARD
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="{{ mix('js/app.js') }}"></script>
	<script>
		window.print();
	</script>
</body>
</html>
