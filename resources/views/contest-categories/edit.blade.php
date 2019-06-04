@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}?activeTab=Categories">{{ $contest->name }}</a>&nbsp;/&nbsp;
			Edit Category
		</h1>
		<form class="form" method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}">
			@csrf
			@method('PATCH')
			<div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
				<label class="label">Category:</label>
				<select name="category_id" class="input">
					<option value="">-</option>
					<?php
					$selected = old('category_id') ? old('category_id') : $contestCategory->category_id;
					?>
					@foreach ($categories as $category)
						<option value="{{ $category->id }}" {{ $category->id == $selected ? 'selected' : '' }}>{{ $category->name }}</option>		
					@endforeach
				</select>
				@error('category_id')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group {{ $errors->has('percentage') ? 'has-error' : '' }}">
				<label class="label">Percentage:</label>
				<input type="text" name="percentage" value="{{ old('percentage') ? old('percentage') : $contestCategory->percentage }}" class="input">
				@error('percentage')
					<div class="error">{{ $message }}</div>
				@enderror
			</div>
			<div class="form-group">
				<button type="submit">Edit</button>
			</div>
		</form>
	</div>
@endsection