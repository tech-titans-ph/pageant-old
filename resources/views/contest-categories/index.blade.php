@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">{{ session('activeContest')->name / Contest Categories }}</h1>
		<div class="bg-white rounded border p-8">
			<div class="mb-5">
				<a href="/contest-categories/create">Create a New Contest Category</a>
			</div>
			<table class="table">
				<thead><tr>
					<th>Name</th>
					<th>Description</th>
					<th></th>
				</tr></thead>
				<tbody>
					@if(count($contestCategories))

					@else
						<tr><td colspan="3">No available Contest Category.</td>/tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
@endsection