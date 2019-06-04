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
			<div class="form-group {{ $errors->has('criteria_id') ? 'has-error' : '' }}">
				<label class="label">Criteria:</label>
				<select name="criteria_id" class="input">
						<option value="">-</option>
					@foreach ($criterias as $criteria)
						<option value="{{ $criteria->id }}" {{ old('criteria_id') == $criteria->id ? 'selected' : '' }}>{{ $criteria->name }}</option>
					@endforeach
				</select>
				@error('criteria_id')
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