@extends('layouts.admin')
@section('content')
	<div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10">
		@pageHeader()
			Contests
		@endpageHeader
		<div class="mt-4 md:mt-0">
			@buttonLink(['href' => route('admin.contests.create')]) Create a New Contest @endbuttonLink
		</div>
	</div>
	@if(session('success'))
		<div class="flex">
			@alert()
				{{ session('success') }}
			@endalert
		</div>
	@endif
	@if(session('error'))
		<div class="flex">
			@alert(['type' => 'error'])
				{{ session('error') }}
			@endalert
		</div>
	@endif
	<div class="flex flex-wrap -mx-2">
		@forelse($contests as $contest)
			<a href="{{ route('admin.contests.show', ['contest' => $contest->id]) }}" class="md:w-1/3 lg:w-1/4 px-2 mb-4">
				@card()
					<img src="{{ Storage::url($contest->logo) }}" class="bg-white object-contain object-center w-full h-64 border rounded mx-auto">
					<div class="text-center mt-4">
						<div class="mb-2 font-medium">{{ $contest->name }}</div>
						<div class="italic">{{ $contest->description }}</div>
					</div>
				@endcard
			</a>
		@empty
			@card() No available Contest(s). @endcard
		@endforelse
	</div>
@endsection