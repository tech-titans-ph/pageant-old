<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contest;
use App\ContestCategory;
use App\CategoryContestant;
use App\Contestant;

class CategoryContestantController extends Controller
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
    public function store(Contest $contest, ContestCategory $contestCategory, Contestant $contestant)
    {
        $data = [
            'contest_category_id' => $contestCategory->id,
            'contestant_id' => $contestant->id,
        ];
        
        CategoryContestant::create($data);
        
        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Contestants')->with('success', 'Contestant has been Added.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContestCategoryContestant  $contestCategoryContestant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest, ContestCategory $contestCategory, CategoryContestant $categoryContestant)
    {
        $categoryContestant->delete();
        
        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Contestants')->with('success', 'Contestant has been Removed.');
    }
}
