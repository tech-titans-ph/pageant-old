@extends('layouts.admin')
@section('content')
	@breadcrumb([
		'links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests'],
			['url' => route('admin.contests.show', ['contest' => $criteria->category->contest->id, 'activeTab' => 'Categories']), 'title' => $criteria->category->contest->name],
			['url' => route('admin.contests.categories.show', ['contest' => $criteria->category->contest->id, 'category' => $criteria->category->id, 'activeTab' => 'Criterias']), 'title' => $criteria->category->name],
		],
		'class' => 'mb-10'
	])
		@pageHeader() Edit Criteria @endpageHeader
	@endbreadcrumb
		
	<div class="flex">
		<div class="w-3/4">
			@card
				<form method="post" action="{{ route('admin.contests.categories.criterias.update', ['contest' => $criteria->category->contest->id, 'category' => $criteria->category->id, 'criteria' => $criteria->id]) }}">
					@csrf
					@method('PATCH')
					@formField(['label' => 'Name', 'error' => 'name'])
						<input-picker
								api="{{ route('admin.criterias.index') }}"
								hidden-name="id"
								display-name="name"
								hidden-property="id"
								display-property="name"
								hidden-value="{{ old('id') ?? $criteria->id }}"
								display-value="{{ old('name') ?? $criteria->name }}"
								placeholder="Enter name of criteria..."
						></input-picker>
					@endformField
					@formField(['label' => 'Percentage', 'error' => 'percentage'])
						<input type="text" name="percentage" class="form-input block w-full" value="{{ old('percentage') ?? $criteria->percentage }}" placeholder="Enter percentage of criteria...">
					@endformField
					@button(['type' => 'Submit']) Edit @endbutton
				</form>
			@endcard
		</div>
	</div>
@endsection