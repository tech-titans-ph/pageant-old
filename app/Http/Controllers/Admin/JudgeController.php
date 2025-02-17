<?php

namespace App\Http\Controllers\Admin;

use App\CategoryJudge;
use App\Contest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateJudgeRequest;
use App\Http\Requests\UpdateJudgeRequest;
use App\Managers\ContestManager;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JudgeController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index()
    {
        $judges = User::whereIs('judge')
            ->when(request('search-keyword'), function ($query) {
                $searchKeyword = request('search-keyword');

                return $query->where('name', 'like', '%' . $searchKeyword . '%');
            })
            ->oldest('name')
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

        if ($judge->categoryJudges()->first()) {
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
}
