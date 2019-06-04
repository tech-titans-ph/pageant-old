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
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">First Name:</label>
				<input type="text" name="name" value="{{ old('name') ? old('name') : $contestant->name }}" class="input">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
				<label class="label">Address:</label>
				<input type="text" name="address" value="{{ old('address') ? old('address') : $contestant->address }}" class="input">
				@error('address')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('number') ? 'has-error' : '' }}">
				<label class="label">Number:</label>
				<input type="text" name="number" value="{{ old('number') ? old('number') : $contestant->number }}" class="input">
				@error('number')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<img src="{{ asset('storage/' . $contestant->picture) }}" class="block rounded-full h-32 w-32 border">
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