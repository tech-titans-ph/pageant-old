@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">Contests</h1>
		<div class="bg-white rounded border p-8">
			<div class="mb-6">
				<a href="/contests/create">Create a New Contest</a>	
			</div>
			@if(session('success'))
				<div class="alert success">{{ session('success') }}</div>
			@endif
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Logo</th>
						<th>Active</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@if(count($contests))
						@foreach($contests as $contest)
							<tr>
								<td>{{ $contest->name }}</td>
								<td>{{ $contest->description }}</td>
								<td><img src="{{ asset('storage/' . $contest->logo) }}" class="h-12 w-12"></td>
								<td class="text-center"><input type="checkbox" {{ $contest->id == session('activeContest')['id'] ? 'checked' : '' }} disabled></td>
								<td>
									<form method="post" action="/contests/{{ $contest->id }}/active" class="inline-block">
										@csrf
										<button type="submit" class="link">Set as Active</button>
									</form>
									<a href="/contests/{{ $contest->id }}/edit">Edit</a>
									<form method="post" action="/contests/{{ $contest->id }}" class="inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="link">Delete</button>
									</form>
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="4">
								No available Contests.
							</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
@endsection