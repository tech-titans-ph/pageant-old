@extends('layouts.admin')
@section('content')
	@breadcrumb([
		'links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests'],
			['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Contestants']), 'title' => $contest->name],
		],
		'class' => 'mb-10',
	])
		@pageHeader() Edit Contestant @endpageHeader
	@endbreadcrumb
		
	<div class="flex">
		<div class="w-3/4">
			@card
				<form method="post" action="{{ route('admin.contests.contestants.update', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}" class="form mx-auto" enctype="multipart/form-data">
					@csrf
					@method('PATCH')
					@formField(['label' => 'Full Name', 'error' => 'name'])
						<input type="text" name="name" value="{{ old('name') ? old('name') : $contestant->name }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Full Name">
					@endformField
					@formField(['label' => 'Description', 'error' => 'description'])
						<textarea name="description" class="form-textarea mt-1 block w-full resize-none" rows="3" placeholder="Enter Contestant Description">{{ old('description') ? old('description') : $contestant->description }}</textarea>
					@endformField
					@formField(['label' => 'Number', 'error' => 'number'])
						<input type="text" name="number" value="{{ old('number') ? old('number') : $contestant->number }}" class="form-input mt-1 block w-full" placeholder="Enter Contestant Number">
					@endformField
					@formField()
						<img src="{{ Storage::url($contestant->picture) }}" class="w-full object-contain object-center border">
					@endformField
					@formField(['label' => 'Change Profile Picture', 'error' => 'picture'])
						<input type="file" name="picture" class="form-input mt-1 block w-full">
					@endformField
					@button(['type' => 'submit']) Edit @endbutton
				</form>
			@endcard
		</div>
	</div>
@endsection