<?php

namespace App\Http\Controllers\Admin;

use App\Contest;
use App\Http\Controllers\Controller;
use App\Http\Requests\{CreateCategoryContestantRequest, RemoveScoreRequest};
use App\Managers\ContestManager;

class CategoryContestantController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function show(Contest $contest, $category, $contestant)
    {
        $category = $contest->categories()->findOrFail($category);

        $contestant = $contest->contestants()->findOrFail($contestant);

        $category = $this->contestManager->getRankedCategoryContestants($category);

        $category->ranked_contestants = $category->ranked_contestants
            ->where('id', '=', $contestant->id);

        return view('admin.category-contestants.show', compact('contest', 'category'));
    }

    public function store(Contest $contest, $category, CreateCategoryContestantRequest $request)
    {
        $category = $contest->categories()->findOrFail($category);

        if ($category->status === 'done') {
            return redirect()
                ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants'])
                ->with('error', 'Could not add Contestant. Please make sure that this category is not yet finished scoring.');
        }

        $category->contestants()->attach($request->validated()['contestant_id'], ['order' => $category->contestants()->count() + 1]);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Added.');
    }

    public function destroy(RemoveScoreRequest $request, Contest $contest, $category, $contestant)
    {
        $category = $contest->categories()->findOrFail($category);

        $contestant = $category->contestants()->where('contestant_id', $contestant)->firstOrFail();

        $this->contestManager->removeCategoryContestant($category, $contestant);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Removed.');
    }

    public function moveUp(Contest $contest, $category, $contestant)
    {
        $category = $contest->categories()->findOrFail($category);

        $contestant = $category->contestants()->findOrFail($contestant);

        $previousContestant = $category->contestants()
            ->where('category_contestants.order', '<', $contestant->pivot->order)
            ->latest('category_contestants.order')
            ->first();

        if ($previousContestant) {
            $order = $contestant->pivot->order;

            $category->contestants()->updateExistingPivot($contestant->id, ['order' => $previousContestant->pivot->order]);

            $category->contestants()->updateExistingPivot($previousContestant->id, ['order' => $order]);
        }

        return redirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants']));
    }

    public function MoveDown(Contest $contest, $category, $contestant)
    {
        $category = $contest->categories()->findOrFail($category);

        $contestant = $category->contestants()->findOrFail($contestant);

        $nextContestant = $category->contestants()
            ->where('category_contestants.order', '>', $contestant->pivot->order)
            ->oldest('category_contestants.order')
            ->first();

        if ($nextContestant) {
            $order = $contestant->pivot->order;

            $category->contestants()->updateExistingPivot($contestant->id, ['order' => $nextContestant->pivot->order]);

            $category->contestants()->updateExistingPivot($nextContestant->id, ['order' => $order]);
        }

        return redirect(route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants']));
    }
}
