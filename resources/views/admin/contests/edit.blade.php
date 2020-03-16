@extends('layouts.admin')
@section('content')
	@breadcrumb([
			'links' => [
				['url' => route('admin.contests.index'), 'title' => 'Contests']
			],
			'class' => 'mb-10'
		])
		@pageHeader()
			Edit Contest
		@endpageHeader
	@endbreadcrumb
	<div class="flex">
		<div class="w-3/4">
			@card
				<form method="post" action="{{ route('admin.contests.update', ['contest' => $contest->id]) }}" enctype="multipart/form-data">
					@csrf
					@method('PATCH')
					@formField(['label' => 'Name', 'error' => 'name'])
						<input type="text" name="name" value="{{ old('name') ? old('name') : $contest->name }}" class="form-input mt-1 block w-full" placeholder="Enter Contest Name">
					@endformField
					@formField(['label' => 'Description', 'error' => 'description'])
						<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter Contest Description">{{ old('description') ? old('description') : $contest->description }}</textarea>
					@endformField
					<label class="block mb-4">
						<img src="{{ Storage::url($contest->logo) }}" class="object-contain object-center bg-white w-64 h-64 border rounded mx-auto">
					</label>
					@formField(['label' => 'Logo', 'error' => 'logo'])
						<input type="file" name="logo" class="form-input mt-1 block w-full">
					@endformField
					@button(['type' => 'submit']) Edit @endbutton	
				</form>
			@endcard
		</div>
	</div>
@endsection