<?php

namespace App\Http\Controllers\Admin;

use App\Contest;
use App\Http\Controllers\Controller;
use App\Http\Requests\{CreateContestantRequest, UpdateContestantRequest};
use App\Managers\ContestManager;

class ContestantController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function show(Contest $contest, $contestant)
    {
        $contestant = $contest->contestants()->findOrFail($contestant);

        abort_if($contest->categories()->whereIn('status', ['que', 'scoring'])->count(), 403, 'Could not print socres. Please make sure that all categories in this contest has finished scoring.');

        $categoryContestants = $contestant->categoryContestants()->orderBy('category_id')->get()->map(function ($categoryContestant) {
            $total = 0;

            foreach ($categoryContestant->categoryScores as $categoryScore) {
                $total += $categoryScore->criteriaScores()->sum('score');
            }

            $averageTotal = $total / $categoryContestant->category->categoryJudges()->count();
            $averagePercentage = ($averageTotal / $categoryContestant->category->criterias()->sum('percentage')) * $categoryContestant->category->percentage;

            $categoryContestant['averageTotal'] = $averageTotal;
            $categoryContestant['averagePercentage'] = $averagePercentage;

            return $categoryContestant;
        });

        $totalPercentage = $categoryContestants->sum('averagePercentage');

        return view('admin.contestants.show', compact('contest', 'contestant', 'categoryContestants', 'totalPercentage'));
    }

    public function create(Contest $contest)
    {
        return view('admin.contestants.create', compact('contest'));
    }

    public function store(CreateContestantRequest $request, Contest $contest)
    {
        $this->contestManager->addContestant($contest, $request->validated());

        return redirect()
            ->route('admin.contests.contestants.create', ['contest' => $contest->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Created.');
    }

    public function edit(Contest $contest, $contestant)
    {
        $contestant = $contest->contestants()->findOrFail($contestant);

        return view('admin.contestants.edit', compact('contest', 'contestant'));
    }

    public function update(UpdateContestantRequest $request, Contest $contest, $contestant)
    {
        $this->contestManager->editContestant(
            $contest->contestants()->findOrFail($contestant),
            $request->validated()
        );

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Edited.');
    }

    public function destroy(Contest $contest, $contestant)
    {
        $contestant = $contest->contestants()->findOrFail($contestant);

        if ($contestant->categories()->count()) {
            return redirect()
                ->route('admin.contests.show', ['contests' => $contest->id, 'activeTab' => 'Contestants'])
                ->with('error', 'Could not Delete Contestant. Please make sure that it is not yet added in any Category.');
        }

        $this->contestManager->removeContestant($contestant);

        return redirect()
            ->route('admin.contests.show', ['contests' => $contest->id, 'activeTab' => 'Contestants'])
            ->with('success', 'Contestant has been Deleted.');
    }
}
