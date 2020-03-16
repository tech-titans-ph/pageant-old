@extends('layouts.mobile')
@section('navbar-right')
	<a href="{{ route('logout') }}" class="px-4 flex justify-center items-center no-underline block h-full hover:bg-gray-200" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
		Logout
	</a>
	<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
		{{ csrf_field() }}
	</form>
@endsection
@section('content')
	<div class="mx-auto pt-12">
		<div class="border-b p-4 text-center leading-normal">
			<h2 class="text-lg font-bold">{{ $judge->contest->name }}</h2>
			<p class="italic font-thin text-sm">Judge: {{ $judge->user->name }}</p>
			<p class="text-sm">Please select a Category below to start scoring.</p>
		</div>
		<div class="md:rounded md:shadow-md mx-auto">
			<div class="md:flex justify-center md:justify-between border-t">
				<div class="w-full md:w-1/2 mx-auto">
					<img src="{{ Storage::url($judge->contest->logo) }}" class="w-full object-contain object-center" alt="Contestant">
				</div>
			</div>
			
			<judge-category api="{{ route('judge.categories.list-categories') }}"></judge-category>
		</div>
	</div>
	<alert-judge api="{{ route('judge.categories.status') }}"></alert-judge>
@endsection