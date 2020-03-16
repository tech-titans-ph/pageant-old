<?php

namespace App\Http\Controllers\Judge;

use App\Category;
use App\Http\Controllers\Controller;
use App\Judge;
use App\Managers\ContestManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index()
    {
        $judge = Judge::find(session('judge'));

        return view('judge.categories.index', compact('judge'));
    }

    public function show(Category $category)
    {
        $judge = Judge::find(session('judge'));

        $categoryJudge = $category->categoryJudges()->where('judge_id', $judge->id)->firstOrFail();

        abort_if('que' === $category->status, 403, 'Could not access score results. Please make sure that this category is not dormant.');

        $categoryContestants = $category->categoryContestants()->get()->map(function ($categoryContestant) use ($categoryJudge) {
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

        $categoryJudge = $category->categoryJudges()->where('judge_id', $judge->id)->firstOrfail();

        $categoryContestants = $category->categoryContestants()->get()->filter(function ($categoryContestant) use ($category, $categoryJudge) {
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

        $categoryJudge = $category->categoryJudges()->where('judge_id', $judge->id)->firstOrfail();

        abort_if($categoryJudge->completed, 403, 'Could not lock scores. Please make sure that scores in this category is not yet locked.');

        $this->contestManager->completeScore($categoryJudge);

        return redirect()
            ->route('judge.categories.edit', ['category' => $category->id])
            ->with('success', 'All of your scores has been locked.');
    }

    public function status()
    {
        $judge = Judge::find(session('judge'));

        $categoryJudge = $judge->categoryJudges()
            ->with(['category'])
            ->whereHas('category', function (Builder $query) {
                $query->where('status', 'scoring');
            })
            ->doesntHave('categoryScores')
            ->first();

        return response()->json($categoryJudge);
    }

    public function listCategories()
    {
        $judge = Judge::find(session('judge'));

        $categoryJudges = $judge->categoryJudges()->with(['category'])->get()->map(function ($categoryJudge) {
            $categoryJudge['url'] = route('judge.categories.contestants.index', ['category' => $categoryJudge->category_id]);

            if ('que' === $categoryJudge->category->status) {
                $categoryJudge['title'] = 'Dormant';
                $categoryJudge['class'] = 'bg-gray-100 text-gray-700';
            } elseif ('scoring' === $categoryJudge->category->status && ! $categoryJudge->completed) {
                $categoryJudge['title'] = 'Active';
                $categoryJudge['class'] = 'bg-blue-300 text-blue-700';
            } else {
                $categoryJudge['title'] = 'Completed';
                $categoryJudge['class'] = 'bg-green-300 text-green-700';
            }

            return $categoryJudge;
        });

        return response()->json($categoryJudges);
    }
}
