@extends('layouts.mobile')
@section('navbar-right')
	<a href="{{ route('judge.categories.show', ['category' => $category->id]) }}" class="px-4 flex justify-center items-center no-underline block h-full hover:bg-gray-200">
		Results
	</a>
@endsection
@section('content')
	@foreach($categoryContestants as $categoryContestant)
		@php
			$totalScore = 0;
			
			$categoryScore = $categoryJudge->categoryScores()->where('category_contestant_id', $categoryContestant->id)->first();
			
			if($categoryScore){
				$totalScore = $categoryScore->criteriaScores()->sum('score');
			}
		@endphp
		<judge-score
			contest-name="{{ $category->contest->name }}"
			category-name="{{ $category->name }}"
			judge-name="{{ $judge->user->name }}"
			contestant-number="{{ $categoryContestant->number }}"
			contestant-name="{{ $categoryContestant->name }}"
			contestant-description="{{ $categoryContestant->description }}"
			contestant-picture="{{ Storage::url($categoryContestant->picture) }}"
			previous-url="{{ $categoryContestants->previousPageUrl() ?? $categoryContestants->url($categoryContestants->lastPage()) }}"
			next-url="{{ $categoryContestants->currentPage() === $categoryContestants->lastPage() ? route('judge.categories.show', ['category' => $category->id]) : $categoryContestants->nextPageUrl() }}"		
			:enabled="{{ $categoryJudge->completed ? 'false' : 'true' }}"
			percentage="{{ $category->criterias()->sum('percentage') }}"
			score="{{ $totalScore }}"
			>
			@foreach($category->criterias as $criteria)
				@php
					$score = 0;
						
					if($categoryScore){
						$criteriaScore = $categoryScore->criteriaScores()->where('criteria_id', $criteria->id)->first();
						
						if($criteriaScore){
							$score = $criteriaScore->score;
						}
					}	
				@endphp
				<criteria-score
					api="{{ route('judge.categories.contestants.update', ['category' => $category->id, 'contestant' => $categoryContestant->contestant_id]) }}"
					id="{{ $criteria->id }}"
					name="{{ $criteria->name }}"
					percentage="{{ $criteria->percentage }}"
					score="{{ $score }}"
					:enabled="{{ $categoryJudge->completed ? 'false' : 'true' }}"
					>
					<template v-slot:decrease-icon>@svg('minus-solid', 'h-4 w-4 fill-current')</template>
					<template v-slot:increase-icon>@svg('plus-solid', 'h-4 w-4 fill-current')</template>
				</criteria-score>
			@endforeach
		</judge-score>
	@endforeach
	@if($category->status !== 'scoring')
		<alert-judge api="{{ route('judge.categories.status') }}"></alert-judge>
	@endif
@endsection