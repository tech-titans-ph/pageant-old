@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}?activeTab=Contestants">{{ $contest->name }}</a>&nbsp;/&nbsp;
				Edit Contestant
		</h1>
		<form method="post" action="/contests/{{ $contest->id }}/contestants/ {{ $contestant->id }}" class="form mx-auto" enctype="multipart/form-data">
			@csrf
			@method('PATCH')
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Full Name</span>
				<input type="text" name="name" value="{{ old('name') ? old('name') : $contestant->name }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Full Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span class="text-gray-700">Description</span>
				<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter Contestant Description">{{ old('description') ? old('description') : $contestant->description }}</textarea>
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('number') ? 'has-error' : '' }}">
				<span class="text-gray-700">Number</span>
				<input type="text" name="number" value="{{ old('number') ? old('number') : $contestant->number }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Number">
				@error('number')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4">
				<img src="{{ asset('storage/' . $contestant->picture) }}" class="block rounded-full h-32 w-32 border">
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