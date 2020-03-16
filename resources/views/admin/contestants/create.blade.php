@extends('layouts.admin')
@section('content')
	@breadcrumb([
		'links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests'],
			['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Contestants']), 'title' => $contest->name],
		],
		'class' => 'mb-10',
	])
		@pageHeader() Create a New Contestant @endpageHeader
	@endbreadcrumb
	
	@if(session('success'))
		<div class="flex">
			@alert() {{ session('success') }} @endalert
		</div>
	@endif
	
	<div class="flex">
		<div class="w-3/4">
			@card
				<form method="post" action="{{ route('admin.contests.contestants.store', ['contest' => $contest->id]) }}" enctype="multipart/form-data">
					@csrf
					@formField(['label' => 'Full Name', 'error' => 'name'])
						<input type="text" name="name" value="{{ old('name') }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Full Name">
					@endformField
					@formField(['label' => 'Description', 'error' => 'description'])
						<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter Contestant Description">{{ old('description') }}</textarea>
					@endformField
					@formField(['label' => 'Number', 'error' => 'number'])
						<input type="text" name="number" value="{{ old('number') }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Number">
					@endformField
					@formField(['label' => 'Profile Picture', 'error' => 'picture'])
						<input type="file" name="picture" class="form-input mt-1 block w-full">
					@endformField
					@button(['type' => 'submit']) Create @endbutton
				</form>
			@endcard
		</div>
	</div>
@endsection