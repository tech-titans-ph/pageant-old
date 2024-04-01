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

        $contest = $this->contestManager->getRankedContestants($contest);

        $contest->ranked_contestants = $contest->ranked_contestants
            ->where('id', '=', $contestant->id);

        $contest->categories->transform(function ($category) use ($contestant) {
            $category->ranked_contestants = $category->ranked_contestants->filter(function ($rankedContestant) use ($contestant) {
                return $rankedContestant->id == $contestant->id;
            });

            return $category;
        });

        return view('admin.contestants.show', compact('contest'));
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
