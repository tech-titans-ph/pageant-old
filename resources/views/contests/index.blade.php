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
					<div class="flex w-6/12 mx-auto">
						<div class="mr-4 w-1/3">
							<a href="/contests/{{ $contest->id }}"><img src="{{ asset('storage/' . $contest->logo) }}" class="bg-contain bg-center h-32 w-32 p-4 rounded border shadow-md mx-auto"></a>
						</div>
						<div class="self-center w-2/3">
							<div class="mb-2 font-medium"><a href="/contests/{{ $contest->id }}">{{ $contest->name }}</a></div>
							<div class="italic">{{ $contest->description }}</div>
						</div>
					</div>
				</li>
			@empty
				<li>No available Contest(s).</li>
			@endforelse
		</ul>
	</div>
@endsection