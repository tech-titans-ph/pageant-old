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

        abort_unless('done' === $category->status, 403, 'Could not acess contestant score. Please make sure that this category has finished scoring.');

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

        if ('done' === $category->status) {
            return redirect()
                ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants'])
                ->with('error', 'Could not add Contestant. Please make sure that this category is not yet finished scoring.');
        }

        $category->categoryContestants()->create($request->validated());

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Added.');
    }

    public function destroy(Contest $contest, $category, $categoryContestant)
    {
        $category = $contest->categories()->findOrFail($category);

        $categoryContestant = $category->categoryContestants()->findOrFail($categoryContestant);

        $this->contestManager->removeCategoryContestant($categoryContestant);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Removed.');
    }
}
