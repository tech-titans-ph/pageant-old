@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/users">Users</a> / Edit User</h1>
		@if(session('error'))
			<div class="alert error">{{ session('error') }}</div>
		@endif
		<div class="form relative">
			<form method="post" action="/users/{{ $user->id }}" enctype="multipart/form-data">
				@csrf
				@method('PATCH')
				<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
					<span class="text-gray-700">Full Name</span>
					<input type="text" name="name" value="{{ old('name') ? old('name') : $user->name }}" class="form-input mt-1 block w-full" placeholder="Enter your Full Name">
						@error('name')
						<div class="error">{{ $message }}</div>
						@enderror
				</label>
				<label class="block mb-4 {{ $errors->has('username') ? 'has-error' : '' }}">
					<span class="text-gray-700">User Name</span>
					<input type="text" name="username" value="{{ old('username') ? old('username') : $user->username }}" class="form-input mt-1 block w-full" placeholder="Enter your User Name">
						@error('username')
						<div class="error">{{ $message }}</div>
						@enderror
				</label>
				<div class="block mb-4">
					<span class="text-gray-700">Role</span>
					<div class="mt-2">
						<?php
						$role = old('role') ? old('role') : $user->role;
						?>
						<label class="inline-flex items-center mr-4">
							<input type="radio" class="form-radio" name="role" value="admin" {{ $role == 'admin' ? 'checked' : '' }}>
							<span class="ml-2">Administrator</span>
						</label>
						<label class="inline-flex items-center">
							<input type="radio" class="form-radio" name="role" value="judge" {{ $role == 'judge' ? 'checked' : '' }}>
							<span class="ml-2">Judge</span>
						</label>
					</div>
				</div>
				<label class="block mb-4">
					<span class="text-gray-700">Description</span>
					<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter User Description">{{ old('description') ? old('description') : $user->description }}</textarea>
				</label>
				@if($user->picture)
					<label class="block mb-4">
						<img src="{{ asset('storage/' . $user->picture) }}" class="block rounded-full h-32 w-32 border">
					</label>
				@endif
				<label class="block mb-4">
					<span class="text-gray-700">Profile Picture</span>
					<input type="file" name="picture" class="form-input mt-1 block w-full">
					@error('picture')
						<div class="error">{{ $message }}</div>
					@enderror
				</label>
				<label class="block">
					<button type="submit">Edit</button>
				</label>
			</form>
			<form method="post" action="/users/{{ $user->id }}" class="inline-block absolute bottom-0 right-0 pb-4 pr-4">
				@csrf
				@method('DELETE')
				<button type="submit" class="bg-red-600 hover:bg-red-600">Delete</a>
			</form>
		</div>
	</div>
@endsection