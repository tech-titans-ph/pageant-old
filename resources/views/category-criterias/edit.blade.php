@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">
			<a href="/contests">Contests</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}">{{ $contest->name }}</a>&nbsp;/&nbsp;
			<a href="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}?activeTab=Criterias">{{ $contestCategory->category->name }}</a>&nbsp;/&nbsp;
			Edit Criteria
		</h1>
		<form class="form" method="post" action="/contests/{{ $contest->id }}/categories/{{ $contestCategory->id }}/criterias/{{ $categoryCriteria->id }}">
			@csrf
			@method('PATCH')
			<div class="form-group {{ $errors->has('criteria_id') ? 'has-error' : '' }}">
				<label class="label">Criteria:</label>
				<select name="criteria_id" class="input">
					<option value="">-</option>
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
			</div>
			<div class="form-group {{ $errors->has('percentage') ? 'has-error' : '' }}">
				<label class="label">Percentage:</label>
				<input type="text" name="percentage" value="{{ old('percentage') ? old('percentage') : $categoryCriteria->percentage }}" class="input">
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