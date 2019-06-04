@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/contests">Contests</a> / Create a New Contest</h1>
		<form method="post" action="/contests" enctype="multipart/form-data" class="form">
			@csrf
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">Name:</label>
				<input type="text" name="name" value="{{ old('name') }}" class="input">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
				<label class="label">Description:</label>
				<input type="text" name="description" value="{{ old('description') }}" class="input">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
				<label class="label">Logo:</label>
				<input type="file" name="logo" placeholder="Logo" class="input">
				@error('logo')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<button type="submit">Create</button>
			</div>
		</form>
	</div>
@endsection