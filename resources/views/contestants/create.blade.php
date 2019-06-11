@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}?activeTab=Contestants ">{{ $contest->name }}</a>&nbsp;/&nbsp;
			Create a New Contestant
		</h1>
		<form method="post" action="/contests/{{ $contest->id }}/contestants" class="form" enctype="multipart/form-data">
			@csrf
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Full Name</span>
				<input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Full Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span class="text-gray-700">Description</span>
				<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter Contestant Description">{{ old('description') }}</textarea>
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('number') ? 'has-error' : '' }}">
				<span class="text-gray-700">Number</span>
				<input type="text" name="number" value="{{ old('number') }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Number">
				@error('number')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('picture') ? 'has-error' : '' }}">
				<span class="text-gray-700">Profile Picture</span>
				<input type="file" name="picture" class="form-input mt-1 block w-full">
				@error('picture')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4">
				<button type="submit">Create</button>
			</label>
		</form>
	</div>
@endsection