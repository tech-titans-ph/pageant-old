<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Managers\ContestManager;
use App\{Category, Judge};

class CategoryController extends Controller
{
    protected $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index()
    {
        $judge = auth('judge')->user();

        $judge->load(['contest']);

        return view('judge.categories.index', compact('judge'));
    }

    public function show(Category $category)
    {
        $judge = auth('judge')->user();

        $judge = $category->judges()->where('judge_id', $judge->id)->firstOrFail();

        abort_if($category->status === 'que', 403, 'Could not access score results. Please make sure that this category is not dormant.');

        $category->load(['contest']);

        $contestants = $category->contestants()->get()->map(function ($contestant) use ($category, $judge) {
            $contestant['points'] = $category->scores()
                ->where('category_judge_id', $judge->pivot->id)
                ->where('category_contestant_id', $contestant->pivot->id)
                ->sum('points');

            return $contestant;
        })->when(request('sort-by') == 'ranking', function ($items) {
            return $items->sortByDesc('points');
        })->when(! request('sort-by'), function ($items) {
            return $items->sortBy('order');
        });

        return view('judge.categories.show', compact('category', 'judge', 'contestants'));
    }

    public function edit(Category $category)
    {
        $judge = auth('judge')->user();

        $judge = $category->judges()->where('judge_id', $judge->id)->firstOrfail();

        $totalPoints = $category->has_criterias ? $category->criterias()->count() : 1;

        $contestants = $category->contestants()
            ->orderBy('order')->get()
            ->filter(function ($contestant) use ($judge, $totalPoints) {
                $query = $contestant->pivot->scores()
                    ->where('category_judge_id', $judge->pivot->id);

                $points = $query->count();

                $zeroPoints = (clone $query)->where('points', '<=', 0)->count();

                return ($points < $totalPoints) || $zeroPoints;
            });

        if ($contestants->isNotEmpty()) {
            session()->flash('error', 'Could not lock scores. Please make sure that the following contestants have complete scores.');
        }

        return view('judge.categories.edit', compact('category', 'judge', 'contestants'));
    }

    public function update(Category $category)
    {
        $judge = auth('judge')->user();

        $judge = $category->judges()->where('judge_id', $judge->id)->firstOrfail();

        abort_if($judge->pivot->completed, 403, 'Could not lock scores. Please make sure that scores in this category is not yet locked.');

        $this->contestManager->completeScore($category, $judge);

        return redirect()
            ->route('judge.categories.edit', ['category' => $category->id])
            ->with('success', 'All of your scores has been locked.');
    }

    public function status()
    {
        $judge = auth('judge')->user();

        $category = $judge->categories()
            ->where('status', 'scoring')
            ->whereDoesntHave('scores')
            ->first();

        return response()->json($category);
    }

    public function listCategories()
    {
        $judge = auth('judge')->user();

        $categories = $judge->categories()
            ->with(['contest'])
            ->orderBy('order')
            ->get()->map(function ($category) {
                $category['url'] = route('judge.categories.contestants.index', ['category' => $category->id]);

                $category['unit'] = $category->contest->scoring_system == 'average' ? '%' : 'points';

                if ($category->status === 'que') {
                    $category['title'] = 'Dormant';
                    $category['class'] = 'bg-gray-100 text-gray-700';
                } elseif ($category->status === 'scoring' && ! $category->completed) {
                    $category['title'] = 'Active';
                    $category['class'] = 'bg-blue-300 text-blue-700';
                } else {
                    $category['title'] = 'Completed';
                    $category['class'] = 'bg-green-300 text-green-700';
                }

                return $category;
            });

        return response()->json($categories);
    }
}
