<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{CreateJudgeRequest, UpdateJudgeRequest};
use App\Managers\ContestManager;
use App\{ Contest,  Judge};

class JudgeController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index()
    {
        $judges = Judge::when(request('search-keyword'), function ($query) {
            $searchKeyword = request('search-keyword');

            return $query->where('name', 'like', '%' . $searchKeyword . '%');
        })->oldest('name')
            ->get();

        return response()->json($judges);
    }

    public function store(CreateJudgeRequest $request, Contest $contest)
    {
        $this->contestManager->addJudge($contest, $request->validated());

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges'])
            ->with('success', 'Judge has been Added.');
    }

    public function edit(Contest $contest, $judge)
    {
        $judge = $contest->judges()->findOrFail($judge);

        return view('admin.judges.edit', compact('contest', 'judge'));
    }

    public function update(UpdateJudgeRequest $request, Contest $contest, $judge)
    {
        $this->contestManager->editJudge(
            $contest->judges()->findOrFail($judge),
            $request->validated()
        );

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges'])
            ->with('success', 'Judge has been Edited.');
    }

    public function destroy(Contest $contest, $judge)
    {
        $judge = $contest->judges()->findOrFail($judge);

        if ($judge->categories()->count()) {
            return redirect()
                ->route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges'])
                ->with('error', 'Could not Delete Judge. Please make sure that it is not yet added in any Category.');
        }

        $this->contestManager->removeJudge($judge);

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges'])
            ->with('success', 'Judge has been Removed.');
    }

    public function login(Contest $contest, $judge)
    {
        $this->contestManager->loginJudge($contest->judges()->findOrFail($judge));

        return redirect()->route('judge.categories.index');
    }

    public function moveUp(Contest $contest, $judge)
    {
        $judge = $contest->judges()->findOrFail($judge);

        $previousJudge = $contest->judges()
            ->where('order', '<', $judge->order)
            ->latest('order')
            ->first();

        if ($previousJudge) {
            $order = $judge->order;

            $judge->update(['order' => $previousJudge->order]);

            $previousJudge->update(['order' => $order]);
        }

        return redirect(route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges']));
    }

    public function moveDown(Contest $contest, $judge)
    {
        $judge = $contest->judges()->findOrFail($judge);

        $nextJudge = $contest->judges()
            ->where('order', '>', $judge->order)
            ->oldest('order')
            ->first();

        if ($nextJudge) {
            $order = $judge->order;

            $judge->update(['order' => $nextJudge->order]);

            $nextJudge->update(['order' => $order]);
        }

        return redirect(route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Judges']));
    }
}
