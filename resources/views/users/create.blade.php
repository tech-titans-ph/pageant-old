@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/users">Users</a> / Create a New User</h1>
		<form method="post" action="/users" class="form" enctype="multipart/form-data">
			@csrf
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Full Name</span>
				<input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full" placeholder="Enter your Full Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('username') ? 'has-error' : '' }}">
				<span class="text-gray-700">User Name</span>
				<input type="text" name="username" value="{{ old('username') }}" class="form-input mt-1 block w-full" placeholder="Enter your User Name">
				@error('username')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('password') ? 'has-error' : '' }}">
				<span class="text-gray-700">Password</span>
				<input type="password" name="password" class="form-input mt-1 block w-full" placeholder="Enter your Password">
				@error('password')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4">
				<span class="text-gray-700">Confirm Password</span>
				<input type="password" name="password_confirmation" class="form-input mt-1 block w-full" placeholder="Confirm your Password">
			</label>
			<div class="block mb-4">
				<span class="text-gray-700">Role</span>
				<div class="mt-2">
					<label class="inline-flex items-center mr-4">
						<input type="radio" class="form-radio" name="role" value="admin" {{ old('role') == 'admin' || !old('role') ? 'checked' : '' }}>
						<span class="ml-2">Administrator</span>
					</label>
					<label class="inline-flex items-center">
						<input type="radio" class="form-radio" name="role" value="judge" {{ old('role') == 'judge' ? 'checked' : '' }}>
						<span class="ml-2">Judge</span>
					</label>
				</div>
			</div>
			<label class="block mb-4">
				<span class="text-gray-700">Description</span>
				<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter User Description">{{ old('description') }}</textarea>
			</label>
			<label class="block mb-4 {{ $errors->has('picture') ? 'has-error' : '' }}">
				<span class="text-gray-700">Profile Picture</span>
				<input type="file" name="picture" class="form-input mt-1 block w-full">
				@error('picture')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block">
				<button type="submit">Create</button>
			</label>
		</form>
	</div>
@endsection