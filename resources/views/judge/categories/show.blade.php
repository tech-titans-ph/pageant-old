@extends('layouts.mobile')
@section('navbar-right')
	<a href="{{ route('logout') }}" class="px-4 flex justify-center items-center no-underline block h-full hover:bg-gray-200" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
		Logout
	</a>
	<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
		{{ csrf_field() }}
	</form>
@endsection
@section('content')
	<div class="mx-auto pt-12">
		<div class="border-b p-4 text-center leading-normal">
			<h2 class="text-lg font-bold">{{ $category->contest->name }}</h2>
			<p class="text-md font-semibold">{{ $category->name }}</p>
			<p class="italic font-thin text-sm">Judge: {{ $judge->user->name }}</p>
			<p class="text-2xl font-bold">Score Results</p>
		</div>
		<div class="md:rounded md:shadow-md mx-auto {{ !$categoryJudge->completed ? 'pb-12' : '' }}">
			@foreach($categoryContestants as $categoryContestant)
				<a class="flex block p-6 border-t" href="{{ route('judge.categories.contestants.index', ['category' => $category->id, 'page' => $categoryContestant->contestant->number]) }}">
					<img src="{{ Storage::url($categoryContestant->contestant->picture) }}" class="flex-none object-cover object-center w-16 h-16 rounded-full border mr-4">
					<div class="flex-grow font-bold self-center mr-4">
						<div class="mb-2">#{{ $categoryContestant->contestant->number }}</div>
						<div>{{ $categoryContestant->contestant->name }}</div>
					</div>
					<div class="flex-shrink self-center text-right text-green-700 font-bold text-3xl">
						{{ $categoryContestant->score }}
					</div>
				</a>
			@endforeach
		</div>
	</div>
	@if(!$categoryJudge->completed)
		<div class="fixed bottom-0 h-12 border-t bg-white w-full flex justify-center items-center">
			<div class="px-2">
				<a href="{{ route('judge.categories.edit', ['category' => $category->id]) }}" class="flex items-center items-around shadow bg-green-600 rounded-full py-1 px-1 text-white">
					<span class="pl-4">Lock Scores</span>
					<svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="12" cy="12" r="10"></circle>
						<polyline points="12 16 16 12 12 8"></polyline>
						<line x1="8" y1="12" x2="16" y2="12"></line>
					</svg>
				</a>
			</div>
		</div>
		<alert-judge api="{{ route('judge.categories.status') }}"></alert-judge>
	@endif
@endsection