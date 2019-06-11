@extends('layouts.admin')
@section('content')
	<div class="pt-8 flex flex-col" id="appContestCategoryShow">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}?activeTab=Categories">{{ $contest->name }}</a>&nbsp;/&nbsp;
			{{ $contestCategory->category->name }}</a>
		</h1>
		@if (session()->has('success'))
			<div class="alert success">{{ session('success') }}</div>
		@endif
		@if (session()->has('error'))
			<div class="alert error">{{ session('error') }}</div>
		@endif
		<div class="box">
			<tabs>
				<tab-item title="Contestants" class="tab-content-p-0" {{ $activeTab == 'Contestants' ? 'active="true"' : ($activeTab ? '' : 'active="true"') }}>
					<ul class="list">
						@foreach ($addedContestants as $contestant)
							<li>
								<div class="flex">
									<div class="w-1/6">
										<img src="{{ asset('storage/' . $contestant->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
									</div>
									<div class="w-4/6 px-4 pt-4">
										<div class="mb-4 font-bold">
											# {{ $contestant->number . ' - ' . $contestant->name }}
											<div class="inline-block bg-blue-500 text-blue-100 py-1 px-2 ml-4 rounded font-normal">Added</div>
										</div>
										<div class="italic">
											{{ $contestant->description }}
										</div>
									</div>
									<div class="w-1/6 text-right whitespace-no-wrap">
										<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/contestants/{{ $contestant->pivot->id }}" class="btn inline-block">
											@csrf
											@method('DELETE')
											<button type="submit" class="bg-red-600 hover:bg-red-600">Remove</button>
										</form>
									</div>
								</div>
							</li>
						@endforeach
						@foreach ($removedContestants as $contestant)
							<li>
								<div class="flex">
									<div class="w-1/6">
										<img src="{{ asset('storage/' . $contestant->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
									</div>
									<div class="w-3/6 px-4 pt-4">
										<div class="mb-4 font-bold">
											# {{ $contestant->number . ' - ' . $contestant->name }}
											<div class="inline-block bg-red-500 text-red-100 py-1 px-2 ml-4 rounded font-normal">Removed</div>
										</div>
										<div class="italic">
											{{ $contestant->description }}
										</div>
									</div>
									<div class="w-2/6 text-right whitespace-no-wrap">
										<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/contestants/{{ $contestant->id }}" class="btn inline-block">
											@csrf
											<button type="submit">Add</button>
										</form>
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</tab-item>
				<tab-item title="Judges" class="tab-content-p-0" {{ $activeTab == 'Judges' ? 'active="true"' : '' }}>
					<ul class="list">
						@foreach ($addedJudges as $judge)
							<li>
								<div class="flex">
									<div class="w-1/6">
										<img src="{{ asset('storage/' . $judge->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
									</div>
									<div class="w-3/6 px-4 pt-4">
										<div class="mb-4 font-bold whitespace-no-wrap">
											{{ $judge->name }}
											<div class="inline-block bg-blue-500 text-blue-100 py-1 px-2 ml-4 rounded font-normal">Added</div>
											<?php
											$incompleteScore = $judge->categoryJudges()->where('contest_category_id', $contestCategory->id)->first()->scores()->where('score', '<=', 0)->count();
											?>
											@if(!$incompleteScore && $contestCategory->status != 'que')
												<div class="inline-block bg-green-300 text-green-900 py-1 px-2 ml-4 rounded font-normal">Completed Scoring</div>
											@endif
										</div>
										<div class="italic">
											{{ $judge->description }}
										</div>
									</div>
									<div class="w-2/6 text-right whitespace-no-wrap">
										@if($contestCategory->status != 'que')
											<a href="/judge-score/{{ $judge->pivot->id }}/login" class="btn">Score</a>
										@endif
										<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/judges/{{ $judge->pivot->id }}" class="btn inline-block">
											@csrf
											@method('DELETE')
											<button type="submit" class="bg-red-600 hover:bg-red-600">Remove</button>
										</form>
									</div>
								</div>
							</li>
						@endforeach
						@foreach ($removedJudges as $judge)
							<li>
								<div class="flex">
									<div class="w-1/6">
										<img src="{{ asset('storage/' . $judge->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
									</div>
									<div class="w-4/6 px-4 pt-4">
										<div class="mb-4 font-bold">
											{{ $judge->name }}
											<div class="inline-block bg-red-500 text-red-100 py-1 px-2 ml-4 rounded font-normal">Removed</div>
										</div>
										<div class="italic">
											{{ $judge->description }}
										</div>
									</div>
									<div class="w-1/6 text-right whitespace-no-wrap">
										<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/judges/{{ $judge->id }}" class="btn inline-block">
											@csrf
											<button type="submit">Add</button>
										</form>
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</tab-item>
				<tab-item title="Criterias" class="tab-content-p-0" {{ $activeTab == 'Criterias' ? 'active="true"' : '' }}>
					<ul class="list">
						<li>
							<a href="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias/create" class="btn">Add A Criteria</a>
						</li>
						@forelse ($criterias as $criteria)
								<li>
									<div class="flex">
										<div class="flex-grow pr-4">
											<a href="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias/{{ $criteria->pivot->id }}/edit">
												{{ $criteria->name }} - {{ $criteria->pivot->percentage }}%
											</a>
										</div>
										<div class="flex-shrink whitespace-no-wrap">
											<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias/{{ $criteria->pivot->id }}" class="inline-block">
												@csrf
												@method('DELETE')
												<button type="submit" class="bg-red-600 hover:bg-red-600">Remove</button>
											</form>
										</div>
									</div>
								</li>
						@empty
								<li>No Available Criteria(s).</li>
						@endforelse
					</ul>
				</tab-item>
				@if($contestCategory->status == 'done')
					<tab-item title="Scores" class="tab-content-p-0" {{ $activeTab == 'Scores' ? 'active="true"' : ''}}>
						<ul class="list">
							@foreach ($scores as $score)
								<li>
									<div class="flex w-3/4 mx-auto">
										<div class="flex w-3/4">
											<div class="">
												<img src="{{ asset('storage/' . $score->contestant->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
											</div>
											<div class="px-4 self-center">
												<div class="mb-4 font-bold">
													# {{ $score->contestant->number . ' - ' . $score->contestant->name }}
												</div>
												<div class="italic">
													{{ $score->contestant->description }}
												</div>
											</div>
										</div>
										<div class="self-center text-green-700 text-6xl font-bold w-1/4">
											<?php
											$averageScore = $score->scores->sum('score') / $score->contestCategory->judges()->count();
											?>
											{{ number_format($averageScore, 2) }}
										</div>
									</div>
								</li>
								<?php
								?>
							@endforeach
						</ul>
					</tab-item>
				@endif
			</tabs>
		</div>
	</div>
@endsection