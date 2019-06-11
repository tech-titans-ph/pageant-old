@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/criterias">Criterias</a> / Edit Criteria</h1>
		<form class="form" method="post" action="/criterias/{{ $criteria->id }}" class="form">
			@csrf
			@method('PATCH')
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Name</span>
				<input type="text" name="name" value="{{ old('name') ? old('name') : $criteria->name }}" class="form-input mt-1 block w-full" placeholder="Enter Criteria Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span class="text-gray-700">Description</span>
				<input type="text" name="description" value="{{ old('description') ? old('description') : $criteria->description }}" class="form-input mt-1 block w-full" placeholder="Enter Criteria Description">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<div class="block">
				<button type="submit">Edit</button>
			</div>
		</form>
	</div>
@endsection()