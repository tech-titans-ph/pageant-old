<?php

namespace App\Http\Controllers\Admin;

use App\Contest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryContestantRequest;
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

    public function destroy(Contest $contest, $category, $contestant)
    {
        $category = $contest->categories()->findOrFail($category);

        $contestant = $category->contestants()->where('contestant_id', $contestant)->firstOrFail();

        $this->contestManager->removeCategoryContestant($category, $contestant);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Removed.');
    }
}
