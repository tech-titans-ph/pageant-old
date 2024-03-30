<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{CreateContestFromScoreRequest, CreateContestRequest, UpdateContestRequest};
use App\Managers\ContestManager;
use App\{Category, Contest, Contestant};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ContestController extends Controller
{
    public $contestManager;

    public function __construct()
    {
        $this->contestManager = new ContestManager();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contests = Contest::latest()->get();

        return view('admin.contests.index', compact('contests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.contests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateContestRequest $request)
    {
        $contest = $this->contestManager->create($request->validated());

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id])
            ->with('success', 'Contest has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest)
    {
        $contest->load(['contestants' => function ($query) {
            $query->orderBy('number');
        }]);

        $status = [
            'que' => 'Pending',
            'scoring' => 'Scoring',
            'done' => 'Completed',
        ];

        $scoredContestants = [];

        if (! $contest->categories()->whereIn('status', ['que', 'scoring'])->count()) {
            $scoredContestants = $this->contestManager->getScoredContestants($contest);
        }

        return view('admin.contests.show', compact('contest', 'status', 'scoredContestants'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest)
    {
        return view('admin.contests.edit', compact('contest'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContestRequest $request, Contest $contest)
    {
        $contest = $this->contestManager->update($contest, $request->validated());

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id])
            ->with('success', 'Contest has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest)
    {
        if ($contest->contestants()->first()) {
            return redirect()
                ->route('admin.contests.show', ['contest' => $contest->id])
                ->with('error', 'Could not Delete Contest. Please make sure that there is no Contestant in this Contest.');
        }

        if ($contest->judges()->first()) {
            return redirect()
                ->route('admin.contests.show', ['contest' => $contest->id])
                ->with('error', 'Could not Delete Contest. Please make sure that there is no Judge in this Contest.');
        }

        if ($contest->categories()->first()) {
            return redirect()
                ->route('admin.contests.show', ['contest' => $contest->id])
                ->with('error', 'Could not Delete Contest. Please make sure that there is no Category in this Contest.');
        }

        $this->contestManager->delete($contest);

        return redirect()
            ->route('admin.contests.index')
            ->with('success', 'Contest has been Deleted.');
    }

    public function print(Contest $contest)
    {
        abort_unless(! $contest->categories()->whereIn('status', ['que', 'scoring'])->count(), 403, 'Could not print socres. Please make sure that all categories in this contest has finished scoring.');

        $contestants = $this->contestManager->getScoredContestants($contest);

        $categories = $contest->categories()->get()->map(function ($category) {
            $category['categoryContestants'] = $this->contestManager->getScoredCategoryContestants($category);

            return $category;
        });

        return view('admin.contests.print', compact('contest', 'contestants', 'categories'));
    }

    public function status()
    {
        $category = Category::with(['contest'])
            ->where('status', 'scoring')
            ->has('judges')
            ->whereDoesntHave('judges', function (Builder $query) {
                $query->where('completed', 0);
            })->first();

        return response()->json($category);
    }

    public function storeFromScore(Contest $contest, CreateContestFromScoreRequest $request)
    {
        if ($contest->categories()->whereIn('status', ['que', 'scoring'])->first()) {
            return redirect()
                ->route('admin.contests.show', ['contest' => $contest->id])
                ->with('error', 'Could not create contest from results. Please make sure that all categories in this contest has finished scoring.');
        }

        $contest = $this->contestManager->createContestFromScore($contest, $request->validated());

        return redirect()
            ->route('admin.contests.show', ['contest' => $contest->id])
            ->with('success', 'Contest has been created from results.');
    }
}
