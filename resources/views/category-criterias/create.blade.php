@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}">{{ $contest->name }}</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}?activeTab=Criterias">{{ $contestCategory->category->name }}</a>&nbsp;/&nbsp;
			Add A Criteria
		</h1>
		<form class="form" method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias?activeTab=Criterias">
			@csrf
			<label class="block mb-4 {{ $errors->has('criteria_id') ? 'has-error' : '' }}">
				<span class="text-gray-700">Criteria</span>
				<select name="criteria_id" class="form-select mt-1 block w-full">
						<option value="">Select Criteria</option>
					@foreach ($criterias as $criteria)
						<option value="{{ $criteria->id }}" {{ old('criteria_id') == $criteria->id ? 'selected' : '' }}>{{ $criteria->name }}</option>
					@endforeach
				</select>
				@error('criteria_id')
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
			<label class="block">
				<button type="submit">Add</button>
			</label>
		</form>
	</div>
@endsection