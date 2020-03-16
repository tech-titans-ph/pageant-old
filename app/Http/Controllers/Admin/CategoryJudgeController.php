<?php

namespace App\Http\Controllers\Admin;

use App\Contest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryJudgeRequest;
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

        if ('done' === $category->status) {
            return redirect()
                ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges'])
                ->with('error', 'Could not add Judge. Please make sure that this Category is not yet finished scoring.');
        }

        $category->categoryJudges()->create($request->validated());

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges'])
            ->with('success', 'Judge has been Added.');
    }

    public function destroy(Contest $contest, $category, $categoryJudge)
    {
        $category = $contest->categories()->findOrFail($category);

        $categoryJudge = $category->categoryJudges()->findOrFail($categoryJudge);

        $this->contestManager->removeCategoryJudge($categoryJudge);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Judges'])
            ->with('success', 'Judge has been Removed.');
    }
}
