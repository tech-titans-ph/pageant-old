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
											{{ $contestant->address }}
										</div>
									</div>
									<div class="w-1/6 text-right whitespace-no-wrap">
										<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/contestants/{{ $contestant->pivot->id }}" class="btn inline-block">
											@csrf
											@method('DELETE')
											<button type="submit">Remove</button>
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
											{{ $contestant->address }}
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
									<div class="w-4/6 px-4 pt-4">
										<div class="mb-4 font-bold">
											{{ $judge->name }}
											<div class="inline-block bg-blue-500 text-blue-100 py-1 px-2 ml-4 rounded font-normal">Added</div>
										</div>
										<div class="italic">
											{{ $judge->description }}
										</div>
									</div>
									<div class="w-1/6 text-right whitespace-no-wrap">
										<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/judges/{{ $judge->pivot->id }}" class="btn inline-block">
											@csrf
											@method('DELETE')
											<button type="submit">Remove</button>
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
											{{ $criteria->name }} - {{ $criteria->pivot->percentage }}%
										</div>
										<div class="flex-shrink whitespace-no-wrap">
											<a href="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias/{{ $criteria->pivot->id }}/edit" class="btn">Edit</a>
											<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias/{{ $criteria->pivot->id }}" class="inline-block">
												@csrf
												@method('DELETE')
												<button type="submit">Remove</button>
											</form>
										</div>
									</div>
								</li>
						@empty
								<li>No Available Criteria(s).</li>
						@endforelse
					</ul>
				</tab-item>
			</tabs>
		</div>
	</div>
@endsection