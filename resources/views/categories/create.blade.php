@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/categories">Categories</a> / Create a New Category</h1>
		<form method="post" action="/categories" class="form">
			@csrf
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Name</span>
				<input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full" placeholder="Enter Category Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span class="text-gray-700">Description</span>
				<input type="text" name="description" value="{{ old('description') }}" class="form-input mt-1 block w-full" placeholder="Enter Category Description">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block">
				<button type="submit">Create</button>
			</label>
		</form>
	</div>
@endsection