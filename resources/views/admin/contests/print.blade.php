<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>{{ config('app.name') }}</title>
	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="text-gray-700 text-sm p-4">
	<div id="app">
		<div class="flex w-1/3 mb-4 mx-auto">
			<div class="flex-none">
				<img src="{{ Storage::url($contest->logo) }}" class="object-contain object-center h-32 w-32 border rounded">
			</div>
			<div class="flex-shrink self-center px-4">
				<div class="font-medium text-2xl">{{ $contest->name }}</div>
			</div>
		</div>
		<div class="px-1 mb-4">
			<p class="font-bold text-2xl mb-4">Summary</p>
			<table class="w-full">
				<thead>
					<tr>
						<th class="p-1 border" colspan="2">Contestants</th>
						<th class="p-1 border">Judges</th>
						@foreach($categories as $category)
							<th class="p-1 border">{{ $category->name }}<br>{{ $category->percentage }}%</th>
						@endforeach
						<th class="p-1 border">Total</th>
					</tr>
				</thead>
				<tbody>
					@php
						$top = 0;
					@endphp
					@foreach($contestants as $contestant)
						@php
							$top++;
						@endphp
						@foreach($contest->judges as $key => $judge)
							<tr>
								@if(!$key)
									<td class="p-1 border align-top text-center" rowspan="{{ $contest->judges()->count() }}">Top {{ $top }}</td>
									<td class="p-1 border align-top w-auto" rowspan="{{ $contest->judges()->count() }}">
										<span class="font-medium">#{{ $contestant->number }} - {{ $contestant->name }}</span>
										<br>
										<span class="italic">{{ $contestant->description }}</span>
									</td>
								@endif
								<td class="p-1 border">{{ $judge->user->name }}</td>
								@foreach($categories as $category)
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
									@endphp
									<td class="p-1 border text-center">{{ round($score, 4) }}</td>
								@endforeach
								@if(!$key)
									<td class="p-1 border text-center font-medium" rowspan="{{ $contest->judges()->count() }}">&nbsp;</td>
								@endif
							</tr>
						@endforeach
						<tr>
							<td class="p-1 border font-bold text-right" colspan="3">Average:</td>
							@foreach($categories as $category)
								<td class="p-1 border font-bold text-center">
									@php
										$categoryContestant = $category->categoryContestants->filter(function($categoryContestant) use ($contestant){
											return $categoryContestant->contestant_id === $contestant->id;
										})->first();
										
									@endphp
									{{ round($categoryContestant['averagePercentage'], 4) }}
								</td>
							@endforeach
							<td class="p-1 border font-bold text-center">{{ round($contestant->totalPercentage, 4) }}</td>
						</tr>
						<tr><td class="p-1 border" colspan="{{ $categories->count() + 4 }}">&nbsp;</td></tr>
					@endforeach
				</tbody>
			</table>

			<p class="font-bold text-2xl">Breakdown</p>
			@foreach($categories as $category)
				<p class="font-bold text-xl border-t my-4">{{ $category->name }} - {{ $category->percentage }}%</p>
				<table class="w-full">
					<thead>
						<tr>
							<th class="p-1 border" colspan="2">Contestants</th>
							<th class="p-1 border">Judges</th>
							@foreach($category->criterias as $criteria)
								<th class="p-1 border">{{ $criteria->name }}<br>{{ $criteria->percentage }}%</th>
							@endforeach
							<th class="p-1 border">Total</th>
							<th class="p-1 border">Percentage</th>
						</tr>
					</thead>
					<tbody>
						@php
							$top = 0;
						@endphp
						@foreach($category->categoryContestants as $categoryContestant)
							@php
								$top++;
							@endphp
							@foreach($category->categoryJudges()->orderBy('judge_id')->get() as $key => $categoryJudge)
								<tr>
									@if(!$key)
										<td class="p-1 border align-top text-center" rowspan="{{ $category->categoryJudges()->count() }}">Top {{ $top }}</td>
										<td class="p-1 border align-top w-auto" rowspan="{{ $category->categoryJudges()->count() }}">
											<span class="font-medium">#{{ $categoryContestant->contestant->number }} - {{ $categoryContestant->contestant->name }}</span>
											<br>
											<span class="italic">{{ $categoryContestant->contestant->description }}</span>
										</td>
									@endif
									<td class="p-1 border">{{ $categoryJudge->judge->user->name }}</td>
									@foreach($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->get() as $criteriaScore)
										<td class="p-1 border text-center">{{ $criteriaScore->score }}</td>
									@endforeach
									<td class="p-1 border text-center font-medium">{{ $categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') }}</td>
									<td class="p-1 border text-center font-medium">
										{{
											round(
												($categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first()->criteriaScores()->sum('score') / $category->criterias()->sum('percentage')) * $category->percentage,
												4
											) 
										}}
									</td>
								</tr>
							@endforeach
							<tr>
								<td class="p-1 border font-bold text-right" colspan="{{ $category->criterias()->count() + 4 }}">Average:</td>
								<td class="p-1 border font-bold text-center">{{ round($categoryContestant->averagePercentage, 4) }}</td>
							</tr>
							<tr><td class="p-1 border" colspan="{{ $category->criterias()->count() + 5 }}">&nbsp;</td></tr>
						@endforeach
					</tbody>
				</table>
			@endforeach
			<div class="w-64 text-center mt-10 ml-auto mr-10">
				Approved By
				<div class="mt-10 border-t-2 font-bold pt-2">
					CHAIRMAN OF THE BOARD
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
