@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/users">Users</a> / Edit User</h1>
		<form method="post" action="/users/{{ $user->id }}" class="form">
			@csrf
			@method('PATCH')
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">Full Name:</label>
				<input type="text" name="name" value="{{ old('name') ? old('name') : $user->name }}" class="input">
					@error('name')
					<div class="error">{{ $message }}</div>
					@enderror
			</div>
			<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
				<label class="label">User Name:</label>
				<input type="text" name="username" value="{{ old('username') ? old('username') : $user->username }}" class="input">
					@error('username')
					<div class="error">{{ $message }}</div>
					@enderror
			</div>
			<div class="form-group">
				<button type="submit">Edit</button>
			</div>
		</form>
	</div>
@endsection