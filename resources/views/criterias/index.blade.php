@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">Criterias</h1>
		<div class="bg-white rounded border p-8">
			<div class="mb-5"><a href="/criterias/create">Create a New Criteria</a></div>
			@if(session('success'))
				<div class="alert success">{{ session('success') }}</div>
			@endif
			<table class="table">
				<thead><tr>
					<th>Name</th>
					<th>Description</th>
					<th></th>
				</tr></thead>
				<tbody>
					@if(count($criterias))
						@foreach($criterias as $criteria)
							<tr>
								<td>{{ $criteria->name }}</td>
								<td>{{ $criteria->description }}</td>
								<td>
									<a href="/criterias/{{ $criteria->id }}/edit">Edit</a>
									<form class="inline-block" method="post" action="/criterias/{{ $criteria->id }}">
										@csrf
										@method('DELETE')
										<button type="submit" class="link">Delete</button>
									</form>
								</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="3">No available Criteria(s).</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
@endsection('content')