@extends('layouts.mobile')
@section('content')
	<div class="mx-auto pt-24">
		@if(session('success'))
			<div class="bg-green-200 text-green-700 font-bold text-center p-6 rounded-lg border-green-700 border-t-8 mx-6">
				{{ session('success') }}
			</div>
			<div class="text-center mt-6">
				<a href="{{ route('judge.categories.index') }}" class="bg-gray-300 inline-block text-gray-700 font-bold px-6 py-3 rounded-full">Select another Category</a>
			</div>
		@elseif(session('error'))
			<div class="bg-red-200 text-red-700 font-bold text-center p-6 rounded-lg border-red-700 border-t-8 mx-6">
				{{ session('error') }}
			</div>
			<div class="text-center mt-6">
				<a href="{{ route('judge.categories.show', ['category' => $category->id]) }}" class="bg-gray-300 inline-block text-gray-700 font-bold px-6 py-3 rounded-full">Continue Scoring</a>
			</div>
			<div class="mt-6 border-b">
				@foreach($categoryContestants as $categoryContestant)
					<a class="flex block p-6 border-t" href="{{ route('judge.categories.contestants.index', ['category' => $category->id, 'page' => $categoryContestant->contestant->number]) }}">
						<img src="{{ Storage::url($categoryContestant->contestant->picture) }}" class="flex-none object-contain object-center w-16 h-16 rounded-full border mr-4">
						<div class="flex-grow font-bold self-center mr-4">
							<div class="mb-2">#{{ $categoryContestant->contestant->number }}</div>
							<div>{{ $categoryContestant->contestant->name }}</div>
						</div>
					</a>
				@endforeach
			</div>
		@else
			<form method="post" action="{{ route('judge.categories.update', ['category' => $category->id]) }}" class="p-6 rounded-lg border border-t-8 mx-6">
				@csrf
				@method('PATCH')
				<div class="text-center font-bold text-gray-700 mb-6">
					Are you sure to Lock all of your scores?
				</div>
				<div class="flex justify-center">
					<button type="submit" class="font-bold inline-block shadow bg-green-600 rounded-full py-3 px-6 text-white mr-6">Yes</button>
					<a href="{{ route('judge.categories.show', ['category' => $category->id]) }}" class="font-bold inline-block shadow bg-red-600 rounded-full py-3 px-6 text-white">No</a>
				</div>
			</form>
		@endif
	</div>
@endsection