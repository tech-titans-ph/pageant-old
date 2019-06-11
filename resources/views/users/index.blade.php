@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">Users</h1>
		@if(session('error'))
			<div class="alert error">{{ session('error') }}</div>
		@endif
		@if(session('success'))
			<div class="alert success">{{ session('success') }}</div>
		@endif
		<ul class="list">
			<li>
				<a href="/users/create" class="btn">Create a New User</a>
			</li>
			@forelse ($users as $user)
					<li>
						<div class="flex">
							<div class="flex-shrink self-center whitespace-no-wrap pr-4 ">
								<div class="mb-2">
									<a href="/users/{{ $user->id }}/edit">{{ $user->username }}</a>
								</div>
								<div class="italic mb-2">{{ $roles[$user->role] }}</div>
								<div>{{ $user->name }}</div>
							</div>
							@if($user->picture)
								<div class="flex-none pr-4">
									<img src="{{ asset('storage/' . $user->picture) }}" class="block rounded-full h-32 w-32 border">
								</div>
							@endif
							<div class="flex-grow self-center">{{ $user->description }}</div>
							@if($user->role == 'judge')
								<div class="flex-none pl-4">
									<a href="/juge/login/{{ $user->id }}" class="btn">Login</a>
								</div>
							@endif
						</div>
					</li>
			@empty
					<li>No available User(s).</li>
			@endforelse
		</ul>
	</div>	
@endsection