@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">{{ session('activeContest')->name }} / Contestants</h1>
		<div class="bg-white rounded border p-8">
			<div class="mb-5">
				<a href="/contestants/create">Create a New Contestant</a>
			</div>
			@if(session('ok'))
				<div class="alert ok">{{ session('ok') }}</div>
			@endif
			@if(session('error'))
				<div class="alert error">{{ session('error') }}</div>
			@endif
			<table class="table">
				<thead><tr>
					<th></th>
					<th>Number</th>
					<th>Full Name</th>
					<th>Address</th>
					<th></th>
				</tr></thead>
				<tbody>
					@if(count($contestants))
						@foreach($contestants as $contestant)
							<tr>
								<td><img src="{{ asset('storage/' . $contestant->picture ) }}" class="block rounded-full h-16 w-16 mx-auto border"></td>
								<td>{{ $contestant->number }}</td>
								<td>{{ $contestant->first_name . ' ' . $contestant->middle_name . ' ' . $contestant->last_name }}</td>
								<td>{{ $contestant->address }}</td>
								<td>
									<a href="/contestants/{{ $contestant->id }}/edit">Edit</a>
									<form method="post" action="/contestants/{{ $contestant->id }}" class="inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="link">Delete</button>
									</form>
								</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="5">No available Contestant(s).</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
@endsection