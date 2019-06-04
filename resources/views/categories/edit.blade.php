@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/categories">Categories</a> / Edit Category</h1>
		<form method="post" action="/categories/{{ $category->id }}" class="form">
			@csrf
			@method('PATCH')
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">Name:</label>
				<input type="text" name="name" value="{{ old('name') ? old('name') : $category->name }}" class="input">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
				<label class="label">Description:</label>
				<input type="text" name="description" value="{{ old('description') ? old('description') : $category->description }}" class="input">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<button type="submit">Edit</button>
			</div>
		</form>
	</div>
@endsection