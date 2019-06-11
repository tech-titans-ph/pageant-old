@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}">{{ $contest->name }}</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}?activeTab=Criterias">{{ $contestCategory->category->name }}</a>&nbsp;/&nbsp;
			Edit Criteria
		</h1>
		@if (session()->has('error'))
			<div class="alert error">{{ session('error') }}</div>
		@endif
		<form class="form" method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias/{{ $categoryCriteria->id }}">
			@csrf
			@method('PATCH')
			<label class="block mb-4 {{ $errors->has('criteria_id') ? 'has-error' : '' }}">
				<span class="text-gray-700">Criteria</span>
				<select name="criteria_id" class="form-select mt-1 block w-full">
					<option value="">Select Criteria</option>
					@foreach ($criterias as $criteria)
						<?php
						if(old('criteria_id')){
							$selected = $criteria->id == old('criteria_id') ? true : false;
						} else {
							$selected = $criteria->id == $categoryCriteria->criteria_id ? true : false;
						}
						?>
						<option value="{{ $criteria->id }}" {{ $selected ? 'selected' : '' }}>{{ $criteria->name }}</option>
					@endforeach
				</select>
				@error('criteria_id')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('percentage') ? 'has-error' : '' }}">
				<span class="text-gray-700">Percentage</span>
				<input type="text" name="percentage" value="{{ old('percentage') ? old('percentage') : $categoryCriteria->percentage }}" class="form-input mt-1 block w-full" placeholder="Enter Percentage">
				@error('percentage')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block">
				<button type="submit">Edit</button>
			</label>
		</form>
	</div>
@endsection