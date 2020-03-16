@extends('layouts.admin')

@section('content')
	@foreach(range(1, 10) as $i)
		@card()
			<form methd="post" action="/sample">
				@csrf
				@formField(['label' => 'E-Mail Address', 'error' => 'email'])
					<input type="email" name="email" class="form-input block w-full">
				@endformField
				@formField(['label' => 'Judge', 'error' => 'name'])
					<input-picker
						hidden-name="user_id"
						display-name="name"
						hidden-property="id"
						display-property="name"
						api="{{ route('admin.contests.judges.index', ['contest' => 1]) }}"
					/>
				@endformField
				@formField(['label' => 'Password', 'error' => 'pasword'])
					<input type="password" name="password" class="form-input block w-full">
				@endformField
				@button(['type' => 'submit'])
					Submit
				@endbutton
			</form>		
		@endcard
	@endforeach
@endsection
