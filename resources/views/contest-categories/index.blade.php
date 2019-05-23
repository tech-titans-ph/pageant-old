@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">{{ session('activeContest')->name }} / Contest Categories</h1>
		<div class="bg-white rounded border p-8">
			<div class="mb-5">
				<a href="/contest-categories/create">Create a New Contest Category</a>
			</div>
			@if(session('success'))
				<div class="alert success">{{ session('success') }}</div>
			@endif
			<table class="table">
				<thead><tr>
					<th>Name</th>
					<th>Description</th>
					<th>Percentage</th>
					<th></th>
				</tr></thead>
				<tbody>
					@if(count($contestCategories))
						@foreach($contestCategories as $contestCategory)
							<tr>
								<td>{{ $contestCategory->name }}</td>
								<td>{{ $contestCategory->description }}</td>
								<td>{{ $contestCategory->percentage }}</td>
								<td>
									<a href="/contest-categories/{{ $contestCategory->id }}/edit">Edit</a>
									<form method="post" action="/contest-categories/{{ $contestCategory->id }}" class="inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="link">Delete</button>
									</form>
								</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="3">No available Contest Category.</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
@endsection