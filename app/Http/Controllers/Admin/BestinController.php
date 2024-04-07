<?php

namespace App\Http\Controllers\Admin;

use App\Contest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBestinRequest;
use App\Managers\ContestManager;

class BestinController extends Controller
{
    protected $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    public function index(Contest $contest)
    {
        return view('admin.bestins.print', compact('contest'));
    }

    public function store(Contest $contest, CreateBestinRequest $request)
    {
        $this->contestManager->addBestin($contest, $request->validated());

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => request()->input('activeTab')])
            ->with('success', 'Best In has been added.');
    }

    public function destroy(Contest $contest, $bestin)
    {
        $bestin = $contest->bestins()->findOrFail($bestin);

        $this->contestManager->deleteBestin($bestin);

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id, 'activeTab' => request()->input('activeTab')])
            ->with('success', 'Best In has been removed.');
    }
}
