<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{CreateCriteriaRequest, UpdateCriteriaRequest};
use App\Managers\ContestManager;
use App\{Contest, Criteria};

class CriteriaController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index()
    {
        $criterias = Criteria::when(request()->has('search-keyword'), function ($query) {
            return $query->where('name', 'like', '%' . request()->query('search-keyword') . '%');
        })
            ->select('name')
            ->groupBy('name')
            ->oldest('name')
            ->get();

        return response()->json($criterias);
    }

    public function store(Contest $contest, $category, CreateCriteriaRequest $request)
    {
        $category = $contest->categories()->findOrFail($category);

        abort_unless($category->has_criterias, 404);

        if ($category->status === 'done') {
            return redirect()
                ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Criterias'])
                ->with('error', 'Could not add Criteria. Please make sure that this Category is not finished scoring.');
        }

        $this->contestManager->addCriteria($category, $request->validated());

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category->id, 'activeTab' => 'Criterias'])
            ->with('success', 'Criteria has been Added.');
    }

    public function edit(Contest $contest, $category, $criteria)
    {
        $criteria = $contest->categories()->findOrFail($category)->criterias()->findOrFail($criteria);

        return view('admin.criterias.edit', compact('criteria'));
    }

    public function update(UpdateCriteriaRequest $request, Contest $contest, $category, $criteria)
    {
        $criteria = $contest->categories()->findOrFail($category)->criterias()->findOrFail($criteria);

        $this->contestManager->editCriteria($criteria, $request->validated());

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category, 'activeTab' => 'Criterias'])
            ->with('success', 'Criteria has been Edited.');
    }

    public function destroy(Contest $contest, $category, $criteria)
    {
        $criteria = $contest->categories()->findOrFail($category)->criterias()->findOrFail($criteria);

        $this->contestManager->removeCriteria($criteria);

        return redirect()
            ->route('admin.contests.categories.show', ['contest' => $contest->id, 'category' => $category, 'activeTab' => 'Criterias'])
            ->with('success', 'Criteria has been Deleted.');
    }
}
