@extends('layouts.admin')
@section('content')
	@breadcrumb([
		'links' => [
			['url' => route('admin.contests.index'), 'title' => 'Contests'],
			['url' => route('admin.contests.show', ['contest' => $category->contest_id, 'activeTab' => 'Categories']), 'title' => $category->contest->name],
		],
		'class' => 'mb-10',
	])
		@pageHeader() Category Details @endpageHeader
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
	
	@error('judge_id')
		<div class="flex">
			@alert(['type' => 'error']) {{ $message }} @endalert
		</div>
	@enderror
	
	@error('contestant_id')
		<div class="flex">
			@alert(['type' => 'error']) {{ $message }} @endalert
		</div>
	@enderror
	
	<div class="flex">
		<div class="w-3/4">
			@card
				<div class="relative">
					<form class="mb-6" method="post" action="{{ route('admin.contests.categories.update', ['contest' => $contest->id, 'category' => $category->id]) }}">
						@csrf
						@method('PATCH')
						@formField(['label' => 'Name', 'error' => 'name'])
							<input-picker
								api="{{ route('admin.categories.index') }}"
								hidden-name="id"
								display-name="name"
								hidden-property="id"
								display-property="name"
								hidden-value="{{ old('id') ?? $category->id }}"
								display-value="{{ old('name') ?? $category->name }}"
								placeholder="Enter name of category..."
							></input-picker>
						@endformField()
						@formField(['label' => 'Percentage', 'error' => 'percentage'])
							<input type="text" name="percentage" class="form-input block w-full" value="{{ old('percentage') ?? $category->percentage }}" placeholder="Enter percentage of category...">
						@endformField
						<div class="mb-6">
							@status(['status' => $category->status]) {{ $status[$category->status] }} @endstatus
						</div>
						@button(['type' => 'submit', 'class' => 'flex-none'])
							Edit
						@endbutton
					</form>
					<div class="absolute bottom-0 right-0">
						@if($category->status == 'que')
							<form method="post" action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'category']) }}" class="inline-block mr-1">
								@csrf
								@method('PATCH')
								@button(['type' => 'submit']) Start Scoring @endbutton
							</form>
						@elseif($category->status == 'scoring')
							<form method="post" action="{{ route('admin.contests.categories.finish', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'category']) }}" class="inline-block mr-1">
								@csrf
								@method('PATCH')
								@button(['type' => 'submit']) Finish Scoring @endbutton	
							</form>
						@elseif($category->status === 'done')
							<form method="post" action="{{ route('admin.contests.categories.start', ['contest' => $contest->id, 'category' => $category->id, 'redirect' => 'category']) }}" class="inline-block mr-1">
								@csrf
								@method('PATCH')
								@button(['type' => 'submit']) Restart Scoring @endbutton
							</form>
						@endif
	
						<form method="post" action="{{ route('admin.contests.categories.destroy', ['contest' => $contest->id, 'category' => $category->id]) }}" class="inline-block">
							@csrf
							@method('DELETE')
							@button(['type' => 'Submit', 'color' => 'danger'])
								Remove
							@endbutton
						</form>
					</div>
				</div>
		
				<tabs>
					<tab-item title="Criterias">
						<ul>
							<li class="p-4">
								<form method="post" action="{{ route('admin.contests.categories.criterias.store', ['contest' => $contest->id, 'category' => $category->id]) }}" class="flex items-start">
									@csrf
									<div class="flex-grow flex mr-4">
										@formField(['error' => 'name', 'class' => 'w-1/2'])
											<input-picker
													api="{{ route('admin.criterias.index') }}"
													hidden-name="id"
													display-name="name"
													hidden-property="id"
													display-property="name"
													hidden-value="{{ old('id') ?? '' }}"
													display-value="{{ old('name') ?? '' }}"
													placeholder="Enter name of criteria..."
											></input-picker>
										@endformField
										@formField(['error' => 'percentage', 'class' => 'w-1/2 ml-4'])
											<input type="text" name="percentage" class="form-input block w-full" value="{{ old('percentage') }}" placeholder="Enter percentage of criteria...">
										@endformField
									</div>
									@button(['type' => 'submit', 'class' => 'flex-none']) Add Criteria @endbutton
								</form>
							</li>
							@forelse ($category->criterias as $criteria)
									<li class="p-4 border-t">
										<div class="flex items-center">
											<a href="{{ route('admin.contests.categories.criterias.edit', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}" class="flex-grow pr-4">
												<div class="font-bold">{{ $criteria->name }}</div>
												<div class="mt-2 italic">{{ $criteria->percentage }}%</div>
											</a>
											<form method="post" action="{{ route('admin.contests.categories.criterias.destroy', ['contest' => $contest->id, 'category' => $category->id, 'criteria' => $criteria->id]) }}" class="flex-none inline-block">
												@csrf
												@method('DELETE')
												@button(['type' => 'submit', 'color' => 'danger']) Remove @endbutton
											</form>
										</div>
									</li>
							@empty
									<li class="p-4 border-t">No Available Criteria(s).</li>
							@endforelse
						</ul>
					</tab-item>
	
					<tab-item title="Judges">
						<ul>
							@forelse ($category->categoryJudges()->orderBy('judge_id')->get() as $key => $judge)
								<li class="p-4 {{ $key ? 'border-t' : '' }}">
									<div class="flex items-center">
										<div class="flex-grow">
											<div class="font-bold">{{ $judge->judge->user->name }}</div>
											<div class="mt-2">
												<span class="px-2 rounded font-normal {{ $judge->completed ? 'bg-green-300 text-green-900' : 'bg-blue-500 text-blue-100' }}">{{ $judge->completed ? 'Completed Scoring' : 'Added' }}</span>
											</div>
										</div>
										<div class="flex-none whitespace-no-wrap ml-4">
											<form method="post" action="{{ route('admin.contests.categories.category-judges.destroy', ['contest' => $contest->id, 'category' => $category->id, 'categoryJudge' => $judge->id]) }}" class="btn inline-block">
												@csrf
												@method('DELETE')
												@button(['type' => 'submit', 'color' => 'danger']) Remove @endbutton
											</form>
										</div>
									</div>
								</li>
							@empty
								<li class="p-4">
									No added judges.
								</li>
							@endforelse
	
							@foreach ($removedJudges as $judge)
								<li class="p-4 border-t">
									<div class="flex items-center">
										<div class="flex-grow">
											<div class="font-bold">{{ $judge->user->name }}</div>
											<div class="mt-2">
												<span class="bg-red-500 text-red-100 px-2 rounded font-normal">Removed</span>
											</div>
										</div>
										<div class="flex-none whitespace-no-wrap ml-4">
											<form method="post" action="{{ route('admin.contests.categories.category-judges.store', ['contest' => $contest->id, 'category' => $category->id]) }}" class="btn inline-block">
												@csrf
												<input type="hidden" name="judge_id" value="{{ $judge->id }}">
												@button(['type' => 'submit']) Add @endbutton
											</form>
										</div>
									</div>
								</li>
							@endforeach
						</ul>
					</tab-item>
	
					<tab-item title="Contestants">
						<ul>
							@forelse ($category->categoryContestants()->get()->sortBy(function($categoryContestant){ return $categoryContestant->contestant->number; }) as $key => $contestant)
								<li class="p-4 {{ $key ? 'border-t' : '' }}">
									<div class="flex">
										<div class="flex-none">
											<img src="{{ Storage::url($contestant->contestant->picture) }}" class="object-cover object-center w-32 h-32 rounded-full border mx-auto">
										</div>
										<div class="flex-grow self-center px-4">
											<div class="font-bold">
												# {{ $contestant->contestant->number . ' - ' . $contestant->contestant->name }}
											</div>
											<div class="mt-2 italic">
												{{ $contestant->contestant->description }}
											</div>
											<div class="mt-2 inline-block bg-blue-500 text-blue-100 py-1 px-2 rounded font-normal">Added</div>
										</div>
										<div class="flex-shrink whitespace-no-wrap">
											<form method="post" action="{{ route('admin.contests.categories.category-contestants.destroy', ['contest' => $contest->id, 'category' => $category->id, 'categoryContestant' => $contestant->id]) }}" class="btn inline-block">
												@csrf
												@method('DELETE')
												@button(['type' => 'submit', 'color' => 'danger']) Remove @endbutton
											</form>
										</div>
									</div>
								</li>
							@empty
								<li class="p-4">
									No added contestants.
								</li>
							@endforelse
	
							@foreach ($removedContestants as $contestant)
								<li class="p-4 border-t">
									<div class="flex">
										<div class="flex-none">
											<img src="{{ Storage::url($contestant->picture) }}" class="object-cover object-center w-32 h-32 rounded-full border mx-auto">
										</div>
										<div class="flex-grow self-center px-4">
											<div class="mb-4 font-bold">
												<div>
													# {{ $contestant->number . ' - ' . $contestant->name }}
												</div>
												<div class="mt-2">
													<span class="inline-block bg-red-500 text-red-100 py-1 px-2 rounded font-normal">Removed</span>
												</div>
											</div>
											<div class="italic">
												{{ $contestant->description }}
											</div>
										</div>
										<div class="flex-shrink whitespace-no-wrap">
											<form method="post" action="{{ route('admin.contests.categories.category-contestants.store', ['contest' => $contest->id, 'category' => $category->id]) }}" class="btn inline-block">
												@csrf
												<input type="hidden" name="contestant_id" value="{{ $contestant->id }}">
												@button(['type' => 'submit']) Add @endbutton
											</form>
										</div>
									</div>
								</li>
							@endforeach
						</ul>
					</tab-item>

					@if($category->status == 'scoring')
						<tab-item title="Real-Time Scores" class="">
							<live-score api="{{ route('admin.contests.categories.live', ['contest' => $contest->id, 'category' => $category->id]) }}"></live-score>
						</tab-item>
					@endif
					
					@if($category->status == 'done')
						<tab-item title="Scores">
							<ul>
								<li class="p-4 flex justify-between">
									@if(!$contest->categories()->whereIn('status', ['que', 'scoring'])->count())
										@buttonLink([
											'href' => route('admin.contests.categories.print', ['contest' => $contest->id, 'category' => $category->id]),
											'attributes' => 'target="_blank"'
										])
											Print Scores
										@endbuttonLink
										@buttonLink(['href' => route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Scores'])]) Summary of Scores @endbuttonLink
									@endif
								</li>
								@php
									$top = 0;
								@endphp
								@foreach ($scoredCategoryContestants as  $contestant)
									@php
										$top++;
									@endphp
									<li class="p-4 border-t">
										<div class="flex justify-between mx-auto">
											<div class="flex flex-grow">
												<div class="flex-none">
													<img src="{{ Storage::url($contestant->contestant->picture) }}" class="object-cover object-center w-32 h-32 rounded-full border">
													<div class="mt-1 text-center text-sm font-medium">Top {{ $top }}</div>
												</div>
												<a href="{{ route('admin.contests.categories.category-contestants.show', ['contest' => $contest->id, 'category' => $category->id, 'categoryContestant' => $contestant->id]) }}" class="px-4 self-center flex flex-col">
													<div class="block mb-2 font-bold">
														# {{ $contestant->contestant->number . ' - ' . $contestant->contestant->name }}
													</div>
													<div class="italic">
														{{ $contestant->contestant->description }}
													</div>
												</a>
											</div>
											<div class="self-center text-green-700 text-6xl font-bold whitespace-no-wrap">
												{{ round($contestant->averagePercentage, 4) }}
											</div>
										</div>
									</li>
								@endforeach
							</ul>
						</tab-item>
						<tab-item	 title="Create Category from Results">
							<form method="post" action="{{ route('admin.contests.categories.store-from-score', ['contest' => $contest->id, 'category' => $category->id]) }}" class="p-4">
								@csrf
								@formField(['error' => 'name', 'label' => 'Name'])
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
								@endformField
								@formField(['error' => 'percentage', 'label' => 'Percentage'])
									<input type="text" name="percentage" class="form-input block w-full" value="{{ old('percentage') }}" placeholder="Enter percentage of category...">
								@endformField
								@formField(['error' => 'contestant_count', 'label' => 'Top Number of Contestants'])
									<input type="text" name="contestant_count" class="form-input block w-full" value="{{ old('contestant_count') }}" placeholder="Enter top number of contestants...">
								@endformField
								<label class="mb-6 flex items-center">
									<div class="mr-4">Include Judges</div>
									<input type="checkbox" name="include_judges" value="1" checked="1" class="form-checkbox">
								</label>
								@button(['type' => 'submit'])
									Create Category
								@endbutton
							</form>
						</tab-item>
					@endif
				</tabs>
			@endcard
		</div>
	</div>
@endsection