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
			<label class="block mb-4 {{ $errors->has('category_id') ? 'has-error' : '' }}">
				<span class="text-gray-700">Category</span>
				<select name="category_id" class="form-select mt-1 block w-full">
					<option value="">Select Category</option>
					@foreach ($categories as $category)
						<option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
					@endforeach
				</select>
				@error('category_id')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('percentage') ? 'has-error' : '' }}">
				<span class="text-gray-700">Percentage</span>
				<input type="text" name="percentage" value="{{ old('percentage') }}" class="form-input mt-1 block w-full" placeholder="Enter Percentage">
				@error('percentage')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4">
				<button type="submit">Add</button>
			</label>
		</form>
	</div>
@endsection