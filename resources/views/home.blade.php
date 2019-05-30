@extends('layouts.admin')
@section('content')
	<div class="pt-8">
		<div class="flex items-center">
			<div class="md:w-1/2 md:mx-auto">
				@if (session('status'))
					<div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">
						{{ session('status') }}
					</div>
				@endif
				<?php
				$title = 'Dashboard';
				if(auth()->user()->role == 'judge'){
					/* $openScore = App\ContestCategory::first()->load([
							'contestants' => function ($query) {
									$query->where('status', 'scoring');
							},
							'judges' => function ($query) {
									$query->where('user_id', auth()->id());
							}
					]);
					if($openScore->contestants->count() && $openScore->judges->count()){
						$title = 'Open for Scoring';
					} */
				}
				?>
				<div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">
					<div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
						{{ $title }}
					</div>
					<div class="w-full p-6">
						<p class="text-gray-700">
							@if($title == 'Open for Scoring')
								<div class="flex mb-4">
									<div class="w-1/4 mr-4">
										<img src="{{ asset('storage/' . $openScore->contestants[0]->picture ) }}" class="block rounded-full h-24 w-24 border mx-auto mb-3">
										<div class="text-2xl text-center font-semibold">#{{ $openScore->contestants[0]->number }}</div>
									</div>
									<div class="w-3/4 pt-4 flex-shrink">
										<div class="mb-4">{{ $openScore->contestants[0]->first_name . ' ' . $openScore->contestants[0]->middle_name . ' ' . $openScore->contestants[0]->last_name }}</div>
										<div class="mb-4">{{ $openScore->name . ' - ' . $openScore->percentage }}%</div>
									</div>
								</div>
								<div class="flex block">
									<div class="mx-auto">
										<a href="/judging" class="btn">Proceed Scoring</a>
									</div>
								</div>
								@else
								You are logged in!
							@endif
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection