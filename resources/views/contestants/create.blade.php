@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}?activeTab=Contestants ">{{ $contest->name }}</a>&nbsp;/&nbsp;
			Create a New Contestant
		</h1>
		<form method="post" action="/contests/{{ $contest->id }}/contestants" class="form" enctype="multipart/form-data">
			@csrf
			<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
				<label class="label">Full Name:</label>
				<input type="text" name="name" value="{{ old('name') }}" class="input">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
				<label class="label">Address:</label>
				<input type="text" name="address" value="{{ old('address') }}" class="input">
				@error('address')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('number') ? 'has-error' : '' }}">
				<label class="label">Number:</label>
				<input type="text" name="number" value="{{ old('number') }}" class="input">
				@error('number')
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