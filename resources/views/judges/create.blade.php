@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">Create a New Judge</h1>
		<div class="bg-white rounded border p-8">
			<form method="post" action="/judges" class="form" enctype="multipart/form-data">
				@csrf
				<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
					<label class="label">Full Name:</label>
					<input type="text" name="name" class="input" value="{{ old('name') }}">
					@error('name')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
					<label class="label">Description:</label>
					<input type="text" name="description" class="input" value="{{ old('description') }}">
					@error('description')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('picture') ? 'has-error' : '' }}">
					<label class="label">Profile Picture:</label>
					<input type="file" name="picture" class="input">
					@error('picture')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
					<label class="label">User Name:</label>
					<input type="text" name="username" class="input" value="{{ old('username') }}">
					@error('username')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
					<label class="label">Password:</label>
					<input type="password" name="password" class="input">
					@error('password')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group">
					<label class="label">Confirm Password:</label>
					<input type="password" name="password_confirmation" class="input">
				</div>
				<div class="form-group">
					<button type="submit">Create</button>
				</div>
			</form>
		</div>
	</div>
@endsection