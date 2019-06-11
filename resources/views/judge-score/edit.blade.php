@extends('layouts.mobile')
@section('content')
	<judge-score
		contest-name="{{ $contestCategory->contest->name }}"
		category-name="{{ $contestCategory->category->name }}"
		judge-name="{{ auth()->user()->name }}"
		contestant-number="{{ $categoryContestant->contestant->number }}"
		contestant-name="{{ $categoryContestant->contestant->name }}"
		contestant-description="{{ $categoryContestant->contestant->description }}"
		contestant-picture="{{ asset('storage/' . $categoryContestant->contestant->picture) }}"
		previous-url="/judge-score/{{ $previousContestant->pivot->id }}"
		next-url="/judge-score/{{ $nextContestant->pivot->id }}"
	>
	@foreach($scores as $score)
		<criteria-score
			score_id="{{ $score->id }}"
			name="{{ $score->categoryCriteria->criteria->name }}"
			percentage="{{ $score->categoryCriteria->percentage }}"
			score="{{ $score->score }}"
		></criteria-score>
	@endforeach
</judge-score>
@endsection