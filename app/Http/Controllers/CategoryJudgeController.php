<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contest;
use App\ContestCategory;
use App\CategoryJudge;
use App\User;

class CategoryJudgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware('adminUser');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contest $contest, ContestCategory $contestCategory, User $judge)
    {
        if ($contestCategory->status != 'que') {
            return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Judges')->with('error', 'Could not Add Judge. Please make sure that the Category has not Started Scoring or Finished Scoring.');
        }

        $data = [
            'contest_category_id' => $contestCategory->id,
            'user_id' => $judge->id
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
        if ($contestCategory->status != 'que') {
            return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Judges')->with('error', 'Could not Remove Judge. Please make sure that the Category has not Started Scoring or Finished Scoring.');
        }

        $categoryJudge->delete();

        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Judges')->with('success', 'Judge has been Removed.');
    }
}
