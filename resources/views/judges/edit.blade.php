@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}?activeTab=Judges">{{ $contest->name }}</a>&nbsp;/&nbsp;
			Edit Judge
		</h1>
		<form method="post" action="/contests/{{ $contest->id }}/judges/{{ $judge->id }}" class="form" enctype="multipart/form-data">
			@csrf
			@method('PATCH')
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Full Name</span>
				<input type="text" name="name" class="form-input mt-1 block w-full" value="{{ old('name') ? old('name') : $judge->name }}" placeholder="Enter Judge Full Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span class="text-gray-700">Description</span>
				<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter Judge Description">{{ old('description') ? old('description') : $judge->description }}</textarea>
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4">
				<img src="{{ asset('storage/' . $judge->picture) }}" class="block rounded-full h-16 w-16 border">
			</label>
			<label class="block mb-4 {{ $errors->has('picture') ? 'has-error' : '' }}">
				<span class="text-gray-700">Change Profile Picture</span>
				<input type="file" name="picture" class="form-input mt-1 block w-full">
				@error('picture')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4">
				<button type="submit">Edit</button>
			</label>
		</form>
	</div>
@endsection