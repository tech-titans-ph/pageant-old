<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetScoreRequest;
use App\Managers\ContestManager;
use App\{Category, Judge};

class ContestantController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index(Category $category)
    {
        $judge = auth('judge')->user();

        $judge = $category->judges()->where('judge_id', $judge->id)->first();

        abort_if($category->status === 'que', 403, 'Could not set score. Please make sure that this category is not dormant.');

        $category->load([
            'contest',
            'criterias' => function ($query) {
                $query->orderBy('order');
            },
            'scores',
        ]);

        $contestants = $category->contestants()
            ->orderBy('order')
            ->paginate(1);

        return view('judge.contestants.index', compact('judge', 'category', 'contestants'));
    }

    public function update(Category $category, $contestant, SetScoreRequest $request)
    {
        $judge = auth('judge')->user();

        $judge = $category->judges()->where('judge_id', $judge->id)->firstOrFail();

        $contestant = $category->contestants()->where('contestant_id', $contestant)->firstOrFail();

        abort_unless($category->status === 'scoring', 403, 'Could not set score. Please make sure that this category has started scoring.');

        abort_if($judge->completed, 403, 'Could not set score. Please make sure that the judge in this category is not yet completed scoring.');

        $data = $request->validated();

        $score = $this->contestManager->setScore($category, $contestant, $data);

        return response()->json([
            'totalScore' => (float) $category->scores()
                ->where('category_judge_id', $score->category_judge_id)
                ->where('category_contestant_id', $score->category_contestant_id)
                ->sum('points'),
        ]);
    }
}
