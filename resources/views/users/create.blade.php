@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/users">Users</a> / Create a New User</h1>
		<form method="post" action="/users" class="form">
			@csrf
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">Full Name:</label>
				<input type="text" name="name" value="{{ old('name') }}" class="input">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
				<label class="label">User Name:</label>
				<input type="text" name="username" value="{{ old('username') }}" class="input">
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
	
@endsection