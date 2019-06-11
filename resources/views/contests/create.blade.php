@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/contests">Contests</a> / Create a New Contest</h1>
		<form method="post" action="/contests" enctype="multipart/form-data" class="form">
			@csrf
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Name</span>
				<input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full" placeholder="Enter Contest Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span class="text-gray-700">Description</span>
				<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter Contest Description">{{ old('description') }}</textarea>
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('logo') ? 'has-error' : '' }}">
				<span class="text-gray-700">Logo</span>
				<input type="file" name="logo" class="form-input mt-1 block w-full">
				@error('logo')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block">
				<button type="submit">Create</button>
			</label>
		</form>
	</div>
@endsection