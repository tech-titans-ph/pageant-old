<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryJudge;
use App\User;
use App\CategoryContestant;
use App\ContestCategory;
use App\Score;

class JudgeScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('adminUser')->only(['login']);

        $this->middleware('judgeUser')->only(['edit']);
    }
    
    public function login(CategoryJudge $categoryJudge)
    {
        $judge = User::find($categoryJudge->user_id);

        $categoryContestant = ContestCategory::find($categoryJudge->contest_category_id)->contestants()->orderBy('number')->first();
        
        auth()->login($judge);
        
        return redirect('/judge-score/' . $categoryContestant->pivot->id);
    }
    
    public function edit(CategoryContestant $categoryContestant)
    {
        // return $categoryContestant->contestant->number;

        $contestCategory = ContestCategory::find($categoryContestant->contest_category_id);

        $categoryJudge = $contestCategory->judges()->where('user_id', auth()->id())->firstOrFail();
        
        $previousContestant = $contestCategory->contestants->where('number', '<', $categoryContestant->contestant->number)->first();
        if (! $previousContestant) {
            $previousContestant = $contestCategory->contestants()->orderBy('number', 'desc')->first();
        }

        $nextContestant = $contestCategory->contestants->where('number', '>', $categoryContestant->contestant->number)->first();
        if (! $nextContestant) {
            $nextContestant = $contestCategory->contestants()->orderBy('number')->first();
        }

        $scores = $contestCategory->scores()
            ->where('category_contestant_id', $categoryContestant->id)
            ->where('category_judge_id', $categoryJudge->pivot->id)
            ->get();
        
        return view('judge-score.edit', compact('categoryContestant', 'contestCategory', 'previousContestant', 'nextContestant', 'scores'));
    }
    
    public function update(Score $score)
    {
        $score->update([
            'score' => request()->input('score')
        ]);

        $data['totalScore'] = Score::where([
            ['contest_category_id', $score->contest_category_id],
            ['category_contestant_id', $score->category_contestant_id],
            ['category_judge_id', $score->category_judge_id],
        ])->sum('score');

        return $data;
    }
}
