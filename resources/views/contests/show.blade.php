@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/contests">Contests</a> / Contest Details</h1>
		@if(session('success'))
			<div class="alert success">{{ session('success') }}</div>
		@endif
		@if(session('error'))
			<div class="alert error">{{ session('error') }}</div>
		@endif
		<div class="box">
			<div class="flex mb-4">
				<div class="w-1/6">
					<img src="{{ asset('storage/' . $contest->logo) }}" class="bg-contain bg-center h-32 w-32 p-4 rounded border shadow-md mx-auto">
				</div>
				<div class="w-3/6 px-4 self-center">
					<div class="mb-2 font-medium">{{ $contest->name }}</div>
					<div class="italic">{{ $contest->description }}</div>
				</div>
				<div class="w-2/6 text-right whitespace-no-wrap">
					<a href="/contests/{{ $contest->id }}/edit" class="btn">Edit</a>
					<form method="post" action="/contests/{{ $contest->id }}" class="inline-block">
						@csrf
						@method('DELETE')
						<button type="submit" class="bg-red-600 hover:bg-red-600">Delete</button>
					</form>
				</div>
			</div>
			<tabs>
				<tab-item title="Contestants" class="tab-content-p-0" {{ $activeTab == 'Contestants' ? 'active="true"': ($activeTab ? '' : 'active="true"') }}>
					<ul class="list">
						<li><a href="/contests/{{ $contest->id }}/contestants/create" class="btn">Create a New Contestant</a></li>
						@forelse ($contest->contestants as $contestant)
								<li>
									<div class="flex">
										<div class="w-1/6">
											<a href="/contests/{{ $contest->id }}/contestants/{{ $contestant->id }}/edit">
												<img src="{{ asset('storage/' . $contestant->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
											</a>
										</div>
										<div class="w-4/6 px-4 self-center">
											<div class="mb-4 font-bold">
												<a href="/contests/{{ $contest->id }}/contestants/{{ $contestant->id }}/edit">
													# {{ $contestant->number . ' - ' . $contestant->name }}
												</a>
											</div>
											<div class="italic">
												{{ $contestant->description }}
											</div>
										</div>
										<div class="w-1/6 text-right whitespace-no-wrap">
											<form method="post" action="/contests/{{ $contest->id }}/contestants/{{ $contestant->id }}" class="btn inline-block">
												@csrf
												@method('DELETE')
												<button type="submit" class="bg-red-600 hover:bg-red-600">Delete</button>
											</form>
										</div>
									</div>
								</li>
						@empty
								<li>No available Contestant(s).</li>
						@endforelse
					</ul>
				</tab-item>
				<tab-item title="Judges" class="tab-content-p-0" {{ $activeTab == 'Judges' ? 'active="true"': '' }}>
					<ul class="list">
						<li><a href="/contests/{{ $contest->id }}/judges/create" class="btn">Create a New Judge</a></li>
						@forelse ($contest->judges as $judge)
							<li>
								<div class="flex">
									<div class="w-1/6">
										<a href="/contests/{{ $contest->id }}/judges/{{ $judge->id }}/edit">
											<img src="{{ asset('storage/' . $judge->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
										</a>
									</div>
									<div class="w-4/6 px-4 self-center">
										<div class="mb-4 font-bold">
											<a href="/contests/{{ $contest->id }}/judges/{{ $judge->id }}/edit">{{ $judge->name }}</a>
										</div>
										<div class="italic">
											{{ $judge->description }}
										</div>
									</div>
									<div class="w-1/6 text-right whitespace-no-wrap">
										<form method="post" action="/contests/{{ $contest->id }}/judges/{{ $judge->id }}" class="btn inline-block">
											@csrf
											@method('DELETE')
											<button type="submit" class="bg-red-600 hover:bg-red-600">Delete</button>
										</form>
									</div>
								</div>
							</li>
						@empty
							<li>No available Judge(s).</li>
						@endforelse
					</ul>
				</tab-item>
				<tab-item title="Categories" class="tab-content-p-0" {{ $activeTab == 'Categories' ? 'active="true"': '' }}>
					<ul class="list">
						<li><a href="/contests/{{ $contest->id }}/categories/create" class="btn">Add a Category</a></li>
						@forelse ($contest->categories as $category)
							<li>
								<div class="flex">
									<div class="flex-grow pr-4">
										<div class="mb-4 font-medium">
											<a href="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}">
												{{ $category->name . ' - ' . $category->pivot->percentage }}%
											</a>
											<div class="status {{ $category->pivot->status }}">{{ $status[$category->pivot->status] }}</div>
										</div>
										<div class="italic">{{ $category->description }}</div>
									</div>
									<div class="flex-shrink whitespace-no-wrap text-right">
										<div class="pb-1">
											@if($category->pivot->status == 'que')
												<a href="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}/scoring" class="btn mr-1">Start Scoring</a>
											@elseif($category->pivot->status == 'scoring')
												<a href="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}/done" class="btn mr-1">Finish Scoring</a>
											@endif
										</div>
										<div>
											<a href="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}/edit" class="btn">Edit</a>
											<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}" class="inline-block">
												@csrf
												@method('DELETE')
												<button type="submit" class="bg-red-600 hover:bg-red-600">Remove</button>
											</form>
										</div>
									</div>
								</div>
							</li>
						@empty
							<li>No available Category.</li>
						@endforelse
					</ul>
				</tab-item>
				@if(!$contest->contestCategories()->whereIn('status', ['que', 'scoring'])->count())
					<tab-item title="Scores" class="tab-content-p-0" {{ $activeTab == 'Scores' ? 'active="true"' : ''}}>
						<ul class="list">
							@forelse($scores as $score)
								<li>
									<div class="flex w-3/4 mx-auto">
										<div class="flex w-3/4">
											<div class="">
												<img src="{{ asset('storage/' . $score->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
											</div>
											<div class="px-4 self-center">
												<div class="mb-4 font-bold">
													# {{ $score->number . ' - ' . $score->name }}
												</div>
												<div class="italic">
													{{ $score->description }}
												</div>
											</div>
										</div>
										<div class="self-center text-green-700 text-6xl font-bold w-1/4">
											<?php
											$contestantScore = 0;
											foreach($score->categoryContestants as $categoryContestant){
												$categoryScore = $categoryContestant->scores->sum('score') / $categoryContestant->contestCategory->judges->count();
												$contestantScore += $categoryScore * ($categoryContestant->contestCategory->percentage / 100);
											}
											?>
											{{ number_format($contestantScore, 2) }}
										</div>
									</div>
								</li>
							@empty
								<li>
									No available Score(s).
								</li>
							@endforelse
						</ul>
					</tab-item>
				@endif
			</tabs>
		</div>
	</div>
@endsection