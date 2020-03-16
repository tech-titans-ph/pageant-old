<?php

namespace App\Http\Controllers\Judge;

use App\Category;
use App\Contestant;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetScoreRequest;
use App\Judge;
use App\Managers\ContestManager;
use Illuminate\Http\Request;

class ContestantController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index(Category $category)
    {
        $judge = Judge::find(session('judge'));

        $categoryJudge = $category->categoryJudges()->where('judge_id', $judge->id)->firstOrFail();

        abort_if('que' === $category->status, 403, 'Could not set score. Please make sure that this category is not dormant.');

        $categoryContestants = $category->categoryContestants()
            ->select('category_contestants.*', 'contestants.name', 'contestants.description', 'contestants.number', 'contestants.picture')
            ->leftJoin('contestants', 'contestants.id', '=', 'category_contestants.contestant_id')
            ->orderBy('contestants.number')
            ->paginate(1);

        return view('judge.contestants.index', compact('judge', 'category', 'categoryJudge', 'categoryContestants'));
    }

    public function update(Category $category, $contestant, SetScoreRequest $request)
    {
        $judge = Judge::find(session('judge'));

        $categoryJudge = $category->categoryJudges()->where('judge_id', $judge->id)->firstOrFail();

        $categoryContestant = $category->categoryContestants()->where('contestant_id', $contestant)->firstOrFail();

        abort_unless('scoring' === $category->status, 403, 'Could not set score. Please make sure that this category has started scoring.');

        abort_if($categoryJudge->completed, 403, 'Could not set score. Please make sure that the judge in this category is not yet completed scoring.');

        $data = $request->validated();

        $criteria = $category->criterias()->find($data['criteria_id']);

        $criteriaScore = $this->contestManager->setScore($categoryContestant, $criteria, $data['score']);

        $categoryScore = $criteriaScore->categoryScore;

        return response()->json([
            'totalScore' => $categoryScore->criteriaScores()->sum('score'),
        ]);
    }
}
