@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id}}?activeTab=Categories">{{ $contest->name }}</a>&nbsp;/&nbsp;
			Add a Category
		</h1>
		<form class="form" method="post" action="/contests/{{ $contest->id }}/categories">
			@csrf
			<div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
				<label class="label">Category:</label>
				<select name="category_id" class="input">
					<option value="">-</option>
					@foreach ($categories as $category)
						<option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
					@endforeach
				</select>
				@error('category_id')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('percentage') ? 'has-error' : '' }}">
				<label class="label">Percentage:</label>
				<input type="text" name="percentage" value="{{ old('percentage') }}" class="input">
				@error('percentage')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<button type="submit">Add</button>
			</div>
		</form>
	</div>
@endsection