@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<h1 class="page-header">Categories</h1>
		@if (session('success'))
			<div class="alert success">{{ session('success') }}</div>
		@endif
		@if (session('error'))
			<div class="alert error">{{ session('error') }} </div>
		@endif
		<ul class="list">
			<li>
				<a href="/categories/create" class="btn">Create a New Category</a>
			</li>
			@forelse ($categories as $category)
				<li>
					<div class="flex">
						<div class="flex-grow pr-4">
							<div class="mb-4">
								<a href="/categories/{{ $category->id }}/edit">{{ $category->name }}</a>
							</div>							
							<div class="italic">{{ $category->description }}</div>
						</div>
						<div class="flex-shrink whitespace-no-wrap">
							<form method="post" action="/categories/{{ $category->id }}" class="inline-block">
								@csrf
								@method('DELETE')
								<button type="submit" class="bg-red-600 hover:bg-red-600">Delete</button>
							</form>
						</div>
					</div>
				</li>
			@empty
				<li>No available Category.</li>
			@endforelse
		</ul>
	</div>
@endsection