@extends('layouts.admin');
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">{{ session('activeContest')->name }} / Judges</h1>
		<div class="bg-white rounded border p-8">
			<div class="mb-5">
				<a href="/judges/create">Create a New Judge</a>
			</div>
			<table class="table">
				<thead><tr>
					<th></th>
					<th>User Name</th>
					<th>Full Name</th>
					<th>Description</th>
					<th></th>
				</tr></thead>
				<tbody>
					@if(count($judges))
						@foreach($judges as $judge)
							<tr>
								<td><img src="{{ asset('storage/' . $judge->picture ) }}" class="block rounded-full h-16 w-16 mx-auto border"></td>
								<td>{{ $judge->username }}</td>
								<td>{{ $judge->name }}</td>
								<td>{{ $judge->description }}</td>
								<td>
									<a href="/judges/{{ $judge->id }}/edit">Edit</a>
									<form method="post" action="/judges/{{ $judge->id }}" class="inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="link">Delete</button>
									</form>
								</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="5">No available Judge(s).</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
@endsection