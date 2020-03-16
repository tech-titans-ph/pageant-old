@extends('layouts.admin')
@section('content')
	@breadcrumb(['links' => [['url' => route('admin.contests.index'), 'title' => 'Contests']], 'class' => 'mb-10'])
		@pageHeader()
			Create a New Contest
		@endpageHeader
	@endbreadcrumb
	@if(session('success'))
		<div class="flex">
			@alert() {{ session('success') }} @endalert
		</div>
	@endif
	<div class="flex">
		<div class="w-3/4">
			@card()
				<form method="post" action="{{ route('admin.contests.store') }}" enctype="multipart/form-data">
					@csrf
					@formField(['label' => 'Name', 'error' => 'name'])
						<input type="text" name="name" value="{{ old('name') }}" class="form-input block w-full" placeholder="Enter Contest Name">
					@endformField
					@formField(['label' => 'Description', 'error' => 'description'])
						<textarea name="description" class="form-textarea block w-full resize-none" rows="3" placeholder="Enter Contest Description">{{ old('description') }}</textarea>
					@endformField
					@formField(['label' => 'Logo', 'error' => 'logo'])
						<input type="file" name="logo" class="form-input block w-full">
					@endformField
					@button(['type' => 'submit'])
						Create
					@endbutton
				</form>
			@endcard
		</div>
	</div>
@endsection