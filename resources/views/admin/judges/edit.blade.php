@extends('layouts.admin')
@section('content')
	@breadcrumb([
		'links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests'],
			['url' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges']), 'title' => $contest->name],
		],
		'class' => 'mb-10',
	])
		@pageHeader()
			Edit Judge
		@endpageHeader
	@endbreadcrumb
	<div class="flex">
		<div class="w-3/4">
			@card
				<form method="post" action="{{ route('admin.contests.judges.update', ['contest' => $contest->id, 'judge' => $judge->id]) }}">
					@csrf
					@method('PATCH')
					@formField(['label' => 'Name', 'error' => 'name'])
						<input-picker
							api="{{ route('admin.judges.index') }}"
							hidden-name="user_id"
							display-name="name"
							hidden-property="id"
							display-property="name"
							hidden-value="{{ old('user_id') ?? $judge->user_id }}"
							display-value="{{ old('name') ?? $judge->user->name }}"
						></input-picker>
						
						@error('user_id')
							<div class="text-red-500 text-xs italic mt-4">{{ $message }}</div>
						@enderror
					@endformField
					@button(['type' => 'submit']) Edit @endbutton
				</form>
			@endcard
		</div>
	</div>
@endsection