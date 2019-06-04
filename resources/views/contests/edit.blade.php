@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/contests">Contests</a> / Edit Contest</h1>
		<form method="post" action="/contests/{{ $contest->id }}" enctype="multipart/form-data" class="form">
			@csrf
			@method('PATCH')
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">Name:</label>
				<input type="text" name="name" value="{{ old('name') ? old('name') : $contest->name }}" class="input">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
				<label class="label">Description:</label>
				<input type="text" name="description" class="input" value="{{ old('description') ? old('description') : $contest->description }}">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<img src="{{ asset('storage/' . $contest->logo ) }}" class="bg-contain bg-center h-32 w-32 p-4 rounded border">
			</div>
			<div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
				<label class="label">Change Logo:</label>
				<input type="file" name="logo" class="input">
				@error('logo')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<button type="submit">Edit</button>
			</div>
		</form>
	</div>
	
@endsection