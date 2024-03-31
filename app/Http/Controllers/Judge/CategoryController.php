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
        $judge = Judge::find(session('judge'));

        $categoryJudge = $categoryJudges()->where('judge_id', $judge->id)->firstOrFail();

        abort_if($category->status === 'que', 403, 'Could not access score results. Please make sure that this category is not dormant.');

        $categoryContestants = $categoryContestants()->get()->map(function ($categoryContestant) use ($categoryJudge) {
            $categoryContestant['score'] = 0;

            $categoryScore = $categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first();

            if ($categoryScore) {
                $categoryContestant['score'] = $categoryScore->criteriaScores()->sum('score');
            }

            return $categoryContestant;
        })->sortByDesc('score');

        return view('judge.categories.show', compact('judge', 'categoryJudge', 'category', 'categoryContestants'));
    }

    public function edit(Category $category)
    {
        $judge = Judge::find(session('judge'));

        $categoryJudge = $categoryJudges()->where('judge_id', $judge->id)->firstOrfail();

        $categoryContestants = $categoryContestants()->get()->filter(function ($categoryContestant) use ($category, $categoryJudge) {
            $categoryScore = $categoryContestant->categoryScores()->where('category_judge_id', $categoryJudge->id)->first();

            if (! $categoryScore) {
                return $categoryContestant;
            }

            foreach ($category->criterias as $criteria) {
                if (! $categoryScore->criteriaScores()->where('criteria_id', $criteria->id)->first()) {
                    return $categoryContestant;
                }
            }
        });

        if ($categoryContestants->isNotEmpty()) {
            session()->flash('error', 'Could not lock scores. Please make sure that the following contestants have complete scores.');
        }

        return view('judge.categories.edit', compact('category', 'judge', 'categoryJudge', 'categoryContestants'));
    }

    public function update(Category $category)
    {
        $judge = Judge::find(session('judge'));

        $categoryJudge = $categoryJudges()->where('judge_id', $judge->id)->firstOrfail();

        abort_if($categoryJudge->completed, 403, 'Could not lock scores. Please make sure that scores in this category is not yet locked.');

        $this->contestManager->completeScore($categoryJudge);

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
