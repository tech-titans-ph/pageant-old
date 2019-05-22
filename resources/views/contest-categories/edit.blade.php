@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">Edit Contest Category</h1>
		<div class="bg-white rounded border p-8">
			<form class="form" method="post" action="/contest-categories/{{ $contestCategory->id }}">
				@csrf
				@method('PATCH')
				<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
					<label class="label">Name:</label>
					<input type="text" name="name" value="{{ old('name') ? old('name') : $contestCategory->name }}" class="input">
					@error('name')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
					<label class="label">Description:</label>
					<input type="text" name="description" value="{{ old('description') ? old('description') : $contestCategory->description }}" class="input">
					@error('description')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('percentage') ? 'has-error' : '' }}">
					<label class="label">Percentage:</label>
					<input type="text" name="percentage" value="{{ old('percentage') ? old('percentage') : $contestCategory->percentage }}" class="input">
					@error('percentage')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group">
					<button type="submit">Edit</button>
				</div>
			</form>
		</div>
	</div>
@endsection