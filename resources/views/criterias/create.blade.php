@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="mb-4">Create a New Criteria</h1>
		<div class="bg-white rounded border p-8">
			<form class="form" method="post" action="/criterias">
				@csrf
				<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
					<label class="label">Name:</label>
					<input type="text" name="name" value="{{ old('name') }}" class="input">
					@error('name')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
					<label for="" class="label">Description:</label>
					<input type="text" name="description" value="{{ old('description') }}" class="input">
					@error('description')
						<div class="error">{{ $message }}</div>
					@enderror
				</div>
				<div class="form-group">
					<button type="submit">Create</button>
				</div>
			</form>
		</div>
	</div>
@endsection('content')