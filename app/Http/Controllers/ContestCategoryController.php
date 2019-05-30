<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Contest;
use App\ContestCategory;
use App\CategoryContestant;
use App\CategoryJudge;
use App\Score;
use Illuminate\Validation\Rule;

class ContestCategoryController extends Controller
{
    public function __construct()
    {
		$this->middleware('auth');
		
		$this->middleware('adminUser');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Contest $contest)
    {
        if (! $contest->contestants->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Add a Category. Please make sure that there is Contestant in this Contest.');
        }

        if (! $contest->judges->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Add a Category. Please make sure that there is Judge in this Contest.');
        }

        $categories = Category::orderBy('name')->get();

        return view('contest-categories.create', compact('contest', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contest $contest)
    {
        $data = request()->validate(
            [
                'category_id' => ['required', 'exists:categories,id', Rule::unique('contest_categories')->where('contest_id', $contest->id)],
                'percentage' => ['required', 'numeric', 'between:1,100']
            ],
            [],
            [
                'category_id' => 'Category',
                'percentage' => 'Percentage',
            ]
        );
        
        $data['contest_id'] = $contest->id;

        $contestCategory = ContestCategory::create($data);
        
        foreach ($contest->contestants as $contestant) {
            CategoryContestant::create([
                'contest_category_id' => $contestCategory->id,
                'contestant_id' => $contestant->id,
            ]);
        }

        foreach ($contest->judges as $judge) {
            CategoryJudge::create([
                'contest_category_id' => $contestCategory->id,
                'judge_id' => $judge->id,
            ]);
        }

        return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('success', 'Category has been Added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest, ContestCategory $contestCategory)
    {
        $addedContestants = $contestCategory->contestants()
            ->orderBy('number')
            ->get();
            
        $removedContestants = $contest->contestants()
            ->orderBy('number')->get()
            ->except($addedContestants->pluck('pivot.contestant_id')->toArray());

        $addedJudges = $contestCategory->judges;
        $removedJudges = $contest->judges->except($addedJudges->pluck('pivot.judge_id')->toArray());

        $criterias = $contestCategory->criterias;

        $activeTab = request()->query('activeTab');

        return view('contest-categories.show', compact('contest', 'contestCategory', 'addedContestants', 'removedContestants', 'addedJudges', 'removedJudges', 'criterias', 'activeTab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest, ContestCategory $contestCategory)
    {
        $categories = Category::orderBy('name')->get();

        return view('contest-categories.edit', compact('contest', 'contestCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contest $contest, ContestCategory $contestCategory)
    {
        $data = request()->validate(
            [
                'category_id' => ['required', 'exists:categories,id', Rule::unique('contest_categories')->ignore($contestCategory)->where('contest_id', $contest->id)],
                'percentage' => ['required', 'numeric', 'between:1,100']
            ],
            [],
            [
                'category_id' => 'Category',
                'percentage' => 'Percentage',
            ]
        );
        
        $contestCategory->update($data);

        return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('success', 'Category has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest, ContestCategory $contestCategory)
    {
        if ($contestCategory->contestants->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Remove Category. Please make sure that there is no added Contestant in this Category.');
        }

        if ($contestCategory->judges->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Remove Category. Please make sure that there is no added Judge in this Category.');
        }

        if ($contestCategory->criterias->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Remove Category. Please make sure that there is no added Criteria in this Category.');
        }

        $contestCategory->delete();

        return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('success', 'Category has been Removed.');
    }
    
    public function scoring(Contest $contest, ContestCategory $contestCategory)
    {
        if ($contest->categories()->whereStatus('scoring')->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Start Scoring. Please make sure that there is no other Category that has already Started Scoring.');
        }

        if ($contestCategory->status != 'que') {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Start Scoring. Please make sure that this Category is not yet Started Scoring or Finished Scoring.');
        }

        if (! $contestCategory->contestants->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Start Scoring. Please make sure that there is a Contestant in this Category.');
        }
    
        if (! $contestCategory->judges->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Start Scoring. Please make sure that there is a Judge in this Category.');
        }
    
        if (! $contestCategory->criterias->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Start Scoring. Please make sure that there is a Criteria in this Category.');
        }

        $data = ['status' => 'scoring'];

		$contestCategory->update($data);
		
		foreach($contestCategory->contestants as $contestant){
			foreach($contestCategory->judges as $judge){
				foreach($contestCategory->criterias as $criteria){
					Score::create([
						'score' => 0,
						'contest_category_id' => $contestCategory->id,
						'category_contestant_id' => $contestant->pivot->id,
						'category_judge_id' => $judge->pivot->id,
						'category_criteria_id' => $criteria->pivot->id,
					]);
				}
			}
		}

        return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('success', 'Category has Started Scoring.');
    }

    public function done(Contest $contest, ContestCategory $contestCategory)
    {
		if($contestCategory->status != 'scoring'){
			return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Finish Scoring. Please make sure that this Category has Started Scoring.');
		}

		if($contestCategory->scores()->where('score', '<=', 0)->count()){
			return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('error', 'Could not Finish Scoring. Please make sure that all Judges has Scores.');
		}

        $data = ['status' => 'done'];

        $contestCategory->update($data);

        return redirect('/contests/' . $contest->id . '?activeTab=Categories')->with('success', 'Category has Finished Scoring.');
    }
}
