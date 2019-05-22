@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">Users</h1>
		<div class="bg-white rounded border p-8">
			<div class="mb-5">
				<a href="/users/create">Create a New User</a>
			</div>
			@if(session('error'))
				<div class="alert error">{{ session('error') }}</div>
			@endif
			@if(session('success'))
				<div class="alert success">{{ session('success') }}</div>
			@endif
			<table class="table">
				<thead>
					<tr>
						<th>User Name</th>
						<th>Full Name</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@if($users)
						@foreach($users as $user)
							<tr>
								<td>{{ $user->username }}</td>
								<td>{{ $user->name }}</td>
								<td>
									<a href="/users/{{ $user->id }}/edit">Edit</a>
									<form method="post" action="/users/{{ $user->id }}" class="inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="link">Delete</a>
									</form>
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="3">No available Users.</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>	
@endsection