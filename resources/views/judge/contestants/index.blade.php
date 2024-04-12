@extends('layouts.mobile')

@section('navbar-right')
  <a href="{{ route('judge.categories.show', ['category' => $category->id]) }}"
    class="flex items-center justify-center h-full px-4 no-underline hover:bg-gray-200">
    Results
  </a>
@endsection

@section('content')
  @foreach ($contestants as $contestant)
    @php
      $totalScore = $category->scores
          ->where('category_judge_id', '=', $judge->pivot->id)
          ->where('category_contestant_id', '=', $contestant->pivot->id)
          ->sum('points');
    @endphp

    <judge-score contest-name="{{ $category->contest->name }}"
      category-name="{{ $category->name }}"
      judge-name="{{ $judge->name }}"
      contestant-number="{{ $contestant->order }}"
      contestant-name="{{ $contestant->name }}"
      contestant-description="{{ $contestant->alias }}"
      contestant-picture="{{ $contestant->avatar_url }}"
      previous-url="{{ $contestants->previousPageUrl() ?? 'javascript:void(0);' }}"
      next-url="{{ $contestants->currentPage() === $contestants->lastPage() ? 'javascript:void(0);' : $contestants->nextPageUrl() }}"
      submit-url="{{ $contestants->currentPage() === $contestants->lastPage() ? route('judge.categories.show', ['category' => $category->id]) : $contestants->nextPageUrl() }}"
      :enabled="{{ $judge->pivot->completed ? 'false' : 'true' }}"
      percentage="{{ $category->has_criterias ? $category->criterias->sum('max_points_percentage') : $category->max_points_percentage }}"
      score="{{ $totalScore }}">
      @forelse ($category->criterias as $criteria)
        @php
          $score =
              $category->scores
                  ->where('category_judge_id', '=', $judge->pivot->id)
                  ->where('category_contestant_id', '=', $contestant->pivot->id)
                  ->where('criteria_id', '=', $criteria->id)
                  ->first()['points'] ?? 0;
        @endphp

        <criteria-score api="{{ route('judge.categories.contestants.update', ['category' => $category->id, 'contestant' => $contestant->id]) }}"
          id="{{ $criteria->id }}"
          name="{{ $criteria->name }}"
          percentage="{{ $criteria->max_points_percentage }}"
          step="{{ $criteria->step }}"
          score="{{ $score }}"
          :enabled="{{ $judge->pivot->completed ? 'false' : 'true' }}">
          <template v-slot:decrease-icon>@svg('minus-solid', 'h-4 w-4 fill-current')</template>
          <template v-slot:increase-icon>@svg('plus-solid', 'h-4 w-4 fill-current')</template>
        </criteria-score>
      @empty
        @php
          $score =
              $category->scores
                  ->where('category_judge_id', '=', $judge->pivot->id)
                  ->where('category_contestant_id', '=', $contestant->pivot->id)
                  ->where('category_id', '=', $category->id)
                  ->first()['points'] ?? 0;
        @endphp

        <criteria-score api="{{ route('judge.categories.contestants.update', ['category' => $category->id, 'contestant' => $contestant->id]) }}"
          id="{{ $category->id }}"
          name="{{ $category->name }}"
          percentage="{{ $category->max_points_percentage }}"
          step="{{ $category->step }}"
          score="{{ $score }}"
          :enabled="{{ $judge->pivot->completed ? 'false' : 'true' }}">
          <template v-slot:decrease-icon>@svg('minus-solid', 'h-4 w-4 fill-current')</template>
          <template v-slot:increase-icon>@svg('plus-solid', 'h-4 w-4 fill-current')</template>
        </criteria-score>
      @endforelse
    </judge-score>
  @endforeach

  @if ($category->status !== 'scoring')
    <alert-judge api="{{ route('judge.categories.status') }}"></alert-judge>
  @endif
@endsection
