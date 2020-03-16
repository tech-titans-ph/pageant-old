@extends('layouts.admin')
@section('content')
	@breadcrumb(['links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests']
		],
		'class' => 'mb-10'
		])
		@pageHeader() Contest Details @endpageHeader
	@endbreadcrumb
	
	@if(session('success'))
		<div class="flex">
			@alert() {{ session('success') }} @endalert
		</div>
	@endif
	@if(session('error'))
		<div class="flex">
			@alert(['type' => 'error']) {{ session('error') }} @endalert
		</div>
	@endif
	<div class="flex">
		<div class="w-full lg:w-3/4">
			@card()
				<div class="flex flex-wrap lg:flex-no-wrap mb-4">
					<img src="{{ Storage::url($contest->logo) }}" class="flex-none mx-auto lg:mr-4 object-contain object-center w-64 h-64 border rounded">
					<div class="flex-grow self-center mt-4 lg:mt-0">
						<div class="mb-4 font-medium">{{ $contest->name }}</div>
						<div class="italic">{{ $contest->description }}</div>
					</div>
					<div class="w-full lg:w-auto lg:flex-none self-start pl-2 text-right whitespace-no-wrap order-first lg:order-none mb-4 lg:mb-0">
						@buttonLink(['href' => route('admin.contests.edit', ['contest' => $contest->id])]) Edit @endbuttonLink
						<form method="post" action="{{ route('admin.contests.destroy', ['contest' => $contest->id]) }}" class="inline-block">
							@csrf
							@method('DELETE')
							@button(['type' => 'submit', 'color' => 'danger']) Delete @endbutton
						</form>
					</div>
				</div>
				<tabs>
					<tab-item title="Judges">
						<ul>
							<li class="p-4">
								<form method="post" action="{{ route('admin.contests.judges.store', ['contest' => $contest->id, 'activeTab' => 'Judges']) }}" class="flex flex-wrap lg:flex-no-wrap items-start">
									@csrf
									@formField(['error' => 'name', 'class' => 'block flex-grow lg:mr-4 mb-6 lg:mb-0'])
										<div>
											<input-picker
												api="{{ route('admin.judges.index') }}"
												hidden-name="user_id"
												display-name="name"
												hidden-property="id"
												display-property="name"
												hidden-value="{{ old('user_id') ?? '' }}"
												display-value="{{ old('name') ?? '' }}"
												placeholder="Enter name of judge..."
											></input-picker>
											
											@error('user_id')
												<div class="text-red-500 text-xs italic mt-4">{{ $message }}</div>
											@enderror
										</div>
									@endformField
									
									@button(['type' => 'submit', 'class' => 'flex-none'])
										Add Judge
									@endbutton
								</form>
							</li>
							@forelse ($contest->judges as $judge)
								<li class="p-4 border-t">
									<div class="flex flex-col lg:flex-row items-start lg:items-center">
										<a href="{{ route('admin.contests.judges.edit', ['contest' => $contest->id, 'judge' => $judge->id]) }}" class="flex-grow lg:pr-2 font-bold">
											{{ $judge->user->name }}
										</a>
										<div class="w-full lg:w-auto flex-none flex justify-between whitespace-no-wrap mt-4 lg:mt-0">
											@buttonLink(['href' => route('admin.contests.judges.login', ['contest' => $contest->id, 'judge' => $judge->id]), 'class' => 'mr-2'])
												Login
											@endbuttonLink
											<form method="post" action="{{ route('admin.contests.judges.destroy', ['contest' => $contest->id, 'judge' => $judge->id]) }}" class="btn inline-block">
												@csrf
												@method('DELETE')
												@button(['type' => 'submit', 'color' => 'danger'])
													Remove
												@endbutton
											</form>
										</div>
									</div>
								</li>
							@empty
								<li class="p-4 border-t">No available Judge(s).</li>
							@endforelse
						</ul>
					</tab-item>
					<tab-item title="Contestants">
						<ul>
							<li class="p-4">
								@buttonLink(['href' => route('admin.contests.contestants.create', ['contest' => $contest->id])])
									Create a New Contestant
								@endbuttonLink
							</li>
							@forelse ($contest->contestants as $contestant)
								<li class="flex flex-wrap lg:flex-no-wrap p-4 border-t">
									<a
										href="{{ route('admin.contests.contestants.edit', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}"
										class="flex-grow flex flex-wrap lg:flex-no-wrap lg:pr-4"
										>
										<img src="{{ Storage::url($contestant->picture) }}" class="flex-none object-cover object-center w-64 h-64 lg:w-32 lg:h-32 rounded-full border mx-auto lg:mx-0">
										<div class="flex-grow self-center px-0 lg:pl-4 mt-4 lg:mt-0">
											<div class="font-bold">
												# {{ $contestant->number . ' - ' . $contestant->name }}
											</div>
											<div class="mt-2 italic">
												{{ $contestant->description }}
											</div>
										</div>
									</a>
									<div class="flex-none w-full md:w-auto lg:pl-4 mt-4 lg:mt-0">
										<form method="post" action="{{ route('admin.contests.contestants.destroy', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}" class="inline-block">
											@csrf
											@method('DELETE')
											@button(['type' => 'submit', 'color' => 'danger']) Delete @endbutton
										</form>
									</div>
								</li>
							@empty
								<li class="p-4 border-t">No available Contestant(s).</li>
							@endforelse
						</ul>
					</tab-item>
					<tab-item title="Categories">
						<ul>
							<li class="p-4">
								<form method="post" action="{{ route('admin.contests.categories.store', ['contest' => $contest->id, 'activeTab' => 'Categories']) }}" class="flex flex-col lg:flex-row items-start">
									@csrf
									<div class="flex-grow flex flex-col lg:flex-row w-full mb-6 lg:mb-0 lg:mr-4">
										@formField(['error' => 'name', 'class' => 'w-full lg:w-1/2'])
											<input-picker
												api="{{ route('admin.categories.index') }}"
												hidden-name="id"
												display-name="name"
												hidden-property="id"
												display-property="name"
												hidden-value="{{ old('id') ?? '' }}"
												display-value="{{ old('name') ?? '' }}"
												placeholder="Enter name of category..."
											></input-picker>
										@endformField()
										@formField(['error' => 'percentage', 'class' => 'w-full lg:w-1/2 lg:ml-4 mt-6 lg:mt-0'])
											<input type="text" name="percentage" class="form-input block w-full" value="{{ old('percentage') }}" placeholder="Enter percentage of category...">
										@endformField
									</div>
									@button(['type' => 'submit', 'class' => 'flex-none'])
										Add Category
									@endbutton
								</form>
							</li>
							@forelse ($contest->categories as $category)
								<li class="p-4 border-t">
									<div class="flex flex-wrap lg:flex-no-wrap">
										<a href="{{ route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id]) }}" class="flex-grow lg:pr-4">
											<div class="flex flex-col">
												<div class="font-bold">{{ $category->name }}</div>
												<div class="mt-2 italic">{{ $category->percentage }}%</div>
												<div class="mt-2">
													@status(['status' => $category->status]) {{ $status[$category->status] }} @endstatus
												</div>
											</div>
										</a>
										<div class="flex-shrink whitespace-no-wrap mt-4 lg:mt-0">
											<div class="pb-1">
												@if($category->status == 'que')
													<form method="post" action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'contest']) }}" class="inline-block mr-1">
														@csrf
														@method('PATCH')
														@button(['type' => 'submit']) Start Scoring @endbutton
													</form>
												@elseif($category->status == 'scoring')
													<form method="post" action="{{ route('admin.contests.categories.finish', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'contest']) }}" class="inline-block mr-1">
														@csrf
														@method('PATCH')
														@button(['type' => 'submit']) Finish Scoring @endbutton	
													</form>
												@elseif($category->status === 'done')
													<form method="post" action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'contest']) }}" class="inline-block mr-1">
														@csrf
														@method('PATCH')
														@button(['type' => 'submit']) Restart Scoring @endbutton
													</form>
												@endif
											</div>
											<div>
												<form method="post" action="{{ route('admin.contests.categories.destroy', ['contest' => $contest->id, 'category' => $category->id]) }}" class="inline-block">
													@csrf
													@method('DELETE')
													@button(['type' => 'Submit', 'color' => 'danger'])
														Remove
													@endbutton
												</form>
											</div>
										</div>
									</div>
								</li>
							@empty
								<li class="p-4 border-t">No available Category.</li>
							@endforelse
						</ul>
					</tab-item>
					@if(!$contest->categories()->whereIn('status', ['que', 'scoring'])->count())
						<tab-item title="Scores">
							<ul>
								<li class="p-4 flex justify-between">
									@buttonLink(['href' => route('admin.contests.print', ['contest' => $contest->id]), 'attributes' => 'target="_blank"']) Print Scores @endbuttonLink
								</li>
								@php
									$top = 0;
								@endphp
								@forelse($scoredContestants as $contestant)
									@php
										$top++;
									@endphp
									<li class="p-4 border-t">
										<a href="{{ route('admin.contests.contestants.show', ['contest' => $contest->id, 'contestant' => $contestant->id]) }}" class="flex flex-wrap lg:flex-no-wrap">
											<div class="flex-none">
												<img src="{{ Storage::url($contestant->picture) }}" class="object-cover object-center w-64 h-64 lg:w-32 lg:h-32 rounded-full border mx-auto lg:ml-0 lg:mr-2">
												<div class="mt-1 text-center text-sm font-medium">Top {{ $top }}</div>
											</div>
											<div class="flex-grow self-center mt-4 lg:mt-0">
												<div class="block font-bold">
													# {{ $contestant->number . ' - ' . $contestant->name }}
												</div>
												<div class="italic mt-2">
													{{ $contestant->description }}
												</div>
											</div>
											<div class="flex-none self-center whitespace-no-wrap text-green-700 text-6xl font-bold lg:pl-2 text-center w-full lg:w-auto">
												{{ round($contestant->totalPercentage, 4) }}
											</div>
										</a>
									</li>
								@empty
									<li class="p-4 border-t">
										No available Score(s).
									</li>
								@endforelse
							</ul>
						</tab-item>
						<tab-item title="Create Contest from Results">
							<form method="post" action="{{ route('admin.contests.store-from-score', ['contest' => $contest->id]) }}" enctype="multipart/form-data" class="p-4">
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
								@formField(['error' => 'contestant_count', 'label' => 'Top Number of Contestants'])
									<input type="text" name="contestant_count" class="form-input block w-full" value="{{ old('contestant_count') }}" placeholder="Enter top number of contestants...">
								@endformField
								<label class="mb-6 flex items-center">
									<div class="mr-4">Include Judges</div>
									<input type="checkbox" name="include_judges" value="1" checked="1" class="form-checkbox">
								</label>
								@button(['type' => 'submit'])
									Create Contest
								@endbutton
							</form>
						</tab-item>
					@endif
				</tabs>
			@endcard
		</div>
	</div>
@endsection