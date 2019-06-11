@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/categories">Categories</a> / Edit Category</h1>
		<form method="post" action="/categories/{{ $category->id }}" class="form">
			@csrf
			@method('PATCH')
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Name</span>
				<input type="text" name="name" value="{{ old('name') ? old('name') : $category->name }}" class="form-input mt-1 block w-full" placeholder="Enter Category Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span class="text-gray-700">Description</span>
				<input type="text" name="description" value="{{ old('description') ? old('description') : $category->description }}" class="form-input mt-1 block w-full" placeholder="Enter Category Description">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block">
				<button type="submit">Edit</button>
			</label>
		</form>
	</div>
@endsection