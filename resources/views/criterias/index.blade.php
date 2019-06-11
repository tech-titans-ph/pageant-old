@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">Criterias</h1>
		@if(session('success'))
			<div class="alert success">{{ session('success') }}</div>
		@endif
		@if(session('error'))
			<div class="alert error">{{ session('error') }}</div>
		@endif
		<ul class="list">
			<li><a href="/criterias/create" class="btn">Create a New Criteria</a></li>
			@forelse ($criterias as $criteria)
					<li>
						<div class="flex">
							<div class="flex-grow pr-4">
								<div class="mb-4">
									<a href="/criterias/{{ $criteria->id }}/edit">{{ $criteria->name }}</a>
								</div>
								<div class="italic">{{ $criteria->description }}</div>
							</div>
							<div class="flex-shrink whitespace-no-wrap">
								<form class="inline-block" method="post" action="/criterias/{{ $criteria->id }}">
									@csrf
									@method('DELETE')
									<button type="submit" class="bg-red-600 hover:bg-red-600">Delete</button>
								</form>
							</div>
						</div>
					</li>
			@empty
					<li>No available Criteria(s).</li>
			@endforelse
		</ul>
	</div>
@endsection('content')