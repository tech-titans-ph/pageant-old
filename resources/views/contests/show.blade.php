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
				<div class="w-3/6 pt-4 px-4">
					<div class="mb-2 font-medium">{{ $contest->name }}</div>
					<div class="italic">{{ $contest->description }}</div>
				</div>
				<div class="w-2/6 text-right whitespace-no-wrap">
					<a href="/contests/{{ $contest->id }}/edit" class="btn">Edit</a>
					<form method="post" action="/contests/{{ $contest->id }}" class="inline-block">
						@csrf
						@method('DELETE')
						<button type="submit">Delete</button>
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
											<img src="{{ asset('storage/' . $contestant->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
										</div>
										<div class="w-4/6 px-4 pt-4">
											<div class="mb-4 font-bold">
												# {{ $contestant->number . ' - ' . $contestant->name }}
											</div>
											<div class="italic">
												{{ $contestant->address }}
											</div>
										</div>
										<div class="w-1/6 text-right whitespace-no-wrap">
											<a href="/contests/{{ $contest->id }}/contestants/{{ $contestant->id }}/edit" class="btn">Edit</a>
											<form method="post" action="/contests/{{ $contest->id }}/contestants/{{ $contestant->id }}" class="btn inline-block">
												@csrf
												@method('DELETE')
												<button type="submit">Delete</button>
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
										<img src="{{ asset('storage/' . $judge->picture) }}" class="bg-center h-32 w-32 rounded-full border mx-auto">
									</div>
									<div class="w-4/6 px-4 pt-4">
										<div class="mb-4 font-bold">
											{{ $judge->name }}
										</div>
										<div class="italic">
											{{ $judge->description }}
										</div>
									</div>
									<div class="w-1/6 text-right whitespace-no-wrap">
										<a href="/contests/{{ $contest->id }}/judges/{{ $judge->id }}/edit" class="btn">Edit</a>
										<form method="post" action="/contests/{{ $contest->id }}/judges/{{ $judge->id }}" class="btn inline-block">
											@csrf
											@method('DELETE')
											<button type="submit">Delete</button>
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
											{{ $category->name . ' - ' . $category->pivot->percentage }}%
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
											<a href="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}" class="btn mr-1">Details</a>
										</div>
										<div>
											<a href="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}/edit" class="btn">Edit</a>
											<form method="post" action="/contests/{{ $contest->id }}/categories/{{ $category->pivot->id }}" class="inline-block">
												@csrf
												@method('DELETE')
												<button type="submit">Remove</button>
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
			</tabs>
		</div>
	</div>
@endsection