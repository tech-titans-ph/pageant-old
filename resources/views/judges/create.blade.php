@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}?activeTab=Judges">{{ $contest->name }}</a>&nbsp;/&nbsp;
			Create a New Judge
		</h1>
		<form method="post" action="/contests/{{ $contest->id }}/judges" class="form" enctype="multipart/form-data">
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
			<div class="form-group">
				<button type="submit">Create</button>
			</div>
		</form>
	</div>
@endsection