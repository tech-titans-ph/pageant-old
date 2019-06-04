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
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">Full Name:</label>
				<input type="text" name="name" class="input" value="{{ old('name') ? old('name') : $judge->name }}">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
				<label class="label">Description:</label>
				<input type="text" name="description" class="input" value="{{ old('description') ? old('description') : $judge->description }}">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<img src="{{ asset('storage/' . $judge->picture) }}" class="block rounded-full h-16 w-16 border">
			</div>
			<div class="form-group {{ $errors->has('picture') ? 'has-error' : '' }}">
				<label class="label">Change Profile Picture:</label>
				<input type="file" name="picture" class="input">
				@error('picture')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<button type="submit">Edit</button>
			</div>
		</form>
	</div>
@endsection