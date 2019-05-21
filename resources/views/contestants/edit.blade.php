@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">Edit Contestant</h1>
		<div class="bg-white rounded border p-8">
			<form method="post" action="/contestants/ {{ $contestant->id }}" class="form" enctype="multipart/form-data">
				@csrf
				@method('PATCH')
				<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
					<label class="label">First Name:</label>
					<input type="text" name="first_name" value="{{ old('first_name') ? old('first_name') : $contestant->first_name }}" class="input">
					@error('first_name')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('middle_name') ? 'has-error' : '' }}">
					<label class="label">Middle Name:</label>
					<input type="text" name="middle_name" value="{{ old('middle_name') ? old('middle_name') : $contestant->middle_name }}" class="input">
					@error('middle_name')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
					<label class="label">Last Name:</label>
					<input type="text" name="last_name" value="{{ old('last_name') ? old('last_name') : $contestant->last_name }}" class="input">
					@error('last_name')
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
				<div class="form-group">
					<img src="{{ asset('storage/' . $contestant->picture) }}" class="block rounded-full h-16 w-16 border">
				</div>
				<div class="form-group {{ $errors->has('picture') ? 'has-error' : '' }}">
					<label class="label">Profile Picture:</label>
					<input type="file" name="picture" class="input">
					@error('picture')
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
					<button type="submit">Edit</button>
				</div>
			</form>
		</div>
	</div>
@endsection