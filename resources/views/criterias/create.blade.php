@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header"><a href="/criterias">Criterias</a> / Create a New Criteria</h1>
		<form class="form" method="post" action="/criterias" class="form>">
			@csrf
			<label class="block mb-4 {{ $errors->has('name') ? 'has-error' : '' }}">
				<span class="text-gray-700">Name</span>
				<input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full" placeholder="Enter Criteria Name">
				@error('name')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block mb-4 {{ $errors->has('description') ? 'has-error' : '' }}">
				<span for="" class="text-gray-700">Description</span>
				<input type="text" name="description" value="{{ old('description') }}" class="form-input mt-1 block w-full" placeholder="Enter Criteria Description">
				@error('description')
					<div class="error">{{ $message }}</div>
				@enderror
			</label>
			<label class="block">
				<button type="submit">Create</button>
			</label>
		</form>
	</div>
@endsection()