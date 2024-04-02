<?php

namespace App\Http\Controllers\Admin;

use App\Contest;
use App\Http\Controllers\Controller;
use App\Http\Requests\{CreateCategoryJudgeRequest, RemoveScoreRequest};
use App\Managers\ContestManager;

class CategoryJudgeController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function store(CreateCategoryJudgeRequest $request, Contest $contest, $category)
    {
        $category = $contest->categories()->findOrFail($category);

        if ($category->status === 'done') {
            return redirect()
                ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges'])
                ->with('error', 'Could not add Judge. Please make sure that this Category is not yet finished scoring.');
        }

        $category->judges()->attach($request->validated()['judge_id'], ['order' => $category->judges()->count() + 1]);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges'])
            ->with('success', 'Judge has been Added.');
    }

    public function destroy(RemoveScoreRequest $request, Contest $contest, $category, $judge)
    {
        $category = $contest->categories()->findOrFail($category);

        $judge = $category->judges()->where('judge_id', $judge)->firstOrFail();

        $this->contestManager->removeCategoryJudge($category, $judge);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges'])
            ->with('success', 'Judge has been Removed.');
    }

    public function moveUp(Contest $contest, $category, $judge)
    {
        $category = $contest->categories()->findOrFail($category);

        $judge = $category->judges()->findOrFail($judge);

        $previousJudge = $category->judges()
            ->where('category_judges.order', '<', $judge->pivot->order)
            ->latest('category_judges.order')
            ->first();

        if ($previousJudge) {
            $order = $judge->pivot->order;

            $category->judges()->updateExistingPivot($judge->id, ['order' => $previousJudge->pivot->order]);

            $category->judges()->updateExistingPivot($previousJudge->id, ['order' => $order]);
        }

        return redirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges']));
    }

    public function MoveDown(Contest $contest, $category, $judge)
    {
        $category = $contest->categories()->findOrFail($category);

        $judge = $category->judges()->findOrFail($judge);

        $nextJudge = $category->judges()
            ->where('category_judges.order', '>', $judge->pivot->order)
            ->oldest('category_judges.order')
            ->first();

        if ($nextJudge) {
            $order = $judge->pivot->order;

            $category->judges()->updateExistingPivot($judge->id, ['order' => $nextJudge->pivot->order]);

            $category->judges()->updateExistingPivot($nextJudge->id, ['order' => $order]);
        }

        return redirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges']));
    }
}
