@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">Contests</h1>
		@if(session('success'))
			<div class="alert success">{{ session('success') }}</div>
		@endif
		@if(session('error'))
			<div class="alert error">{{ session('error') }}</div>
		@endif
		<ul class="list">
			<li>
				<a href="/contests/create" class="btn">Create a New Contest</a>
			</li>
			@forelse($contests as $contest)
				<li>
					<div class="flex">
						<div class="w-1/6">
							<img src="{{ asset('storage/' . $contest->logo) }}" class="bg-contain bg-center h-32 w-32 p-4 rounded border shadow-md mx-auto">
						</div>
						<div class="w-3/6 pt-4 px-4">
							<div class="mb-2 font-medium">{{ $contest->name }}</div>
							<div class="italic">{{ $contest->description }}</div>
						</div>
						<div class="w-2/6 text-right whitespace-no-wrap">
							<a href="/contests/{{ $contest->id }}" class="btn">Details</a>
							<a href="/contests/{{ $contest->id }}/edit" class="btn">Edit</a>
							<form method="post" action="/contests/{{ $contest->id }}" class="inline-block">
								@csrf
								@method('DELETE')
								<button type="submit">Delete</button>
							</form>
						</div>
					</div>
				</li>
			@empty
				<li>No available Contest(s).</li>
			@endforelse
		</ul>
	</div>
@endsection