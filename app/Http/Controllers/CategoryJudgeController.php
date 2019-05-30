<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contest;
use App\ContestCategory;
use App\CategoryJudge;
use App\Judge;

class CategoryJudgeController extends Controller
{
    public function __construct()
    {
		$this->middleware('auth');
		
		$this->middlware('adminUser');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contest $contest, ContestCategory $contestCategory, Judge $judge)
    {
        $data = [
            'contest_category_id' => $contestCategory->id,
            'judge_id' => $judge->id
        ];

        CategoryJudge::create($data);

        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Judges')->with('success', 'Judge has been Added.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContestCategoryJudge  $contestCategoryJudge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest, ContestCategory $contestCategory, CategoryJudge $categoryJudge)
    {
        $categoryJudge->delete();

        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Judges')->with('success', 'Judge has been Removed.');
    }
}
