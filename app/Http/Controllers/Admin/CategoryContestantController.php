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

    public function show(Contest $contest, $category, $categoryContestant)
    {
        $category = $contest->categories()->findOrFail($category);

        $categoryContestant = $category->categoryContestants()->findOrFail($categoryContestant);

        abort_unless($category->status === 'done', 403, 'Could not acess contestant score. Please make sure that this category has finished scoring.');

        $total = 0;

        foreach ($categoryContestant->categoryScores as $categoryScore) {
            $total += $categoryScore->criteriaScores()->sum('score');
        }

        $averageTotal = $total / $category->categoryJudges()->count();
        $averagePercentage = ($averageTotal / $category->criterias()->sum('percentage')) * $category->percentage;

        return view('admin.category-contestants.show', compact('contest', 'category', 'categoryContestant', 'averageTotal', 'averagePercentage'));
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
