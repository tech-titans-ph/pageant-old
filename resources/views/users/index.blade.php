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
							<div class="flex-grow pr-4">
								<div class="mb-2">{{ $user->username }}</div>
								<div class="italic">{{ $user->name }}</div>
							</div>
							<div class="flex-shrink whitespace-no-wrap">
								<a href="/users/{{ $user->id }}/edit" class="btn">Edit</a>
								<form method="post" action="/users/{{ $user->id }}" class="inline-block">
									@csrf
									@method('DELETE')
									<button type="submit">Delete</a>
								</form>
							</div>
						</div>
					</li>
			@empty
					<li>No available User(s).</li>
			@endforelse
		</ul>
	</div>	
@endsection