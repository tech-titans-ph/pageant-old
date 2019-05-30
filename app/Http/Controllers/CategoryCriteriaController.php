<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contest;
use App\ContestCategory;
use App\CategoryCriteria;
use App\Criteria;
use Illuminate\Validation\Rule;

class CategoryCriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function create(Contest $contest, ContestCategory $contestCategory)
    {
        $criterias = Criteria::orderBy('name')->get();
        
        return view('category-criterias.create', compact('contest', 'contestCategory', 'criterias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contest $contest, ContestCategory $contestCategory)
    {
        $data = request()->validate(
            [
                'criteria_id' => [
                    'required',
                    'numeric',
                    'exists:criterias,id',
                    Rule::unique('category_criterias')->where('contest_category_id', $contestCategory->id)
                ],
                'percentage' => ['required', 'numeric', 'between:1,100'],
            ],
            [],
            [
                'criteria_id' => 'Criteria',
                'percentage' => 'Percentage',
            ]
        );

        $data['contest_category_id'] = $contestCategory->id;
        
        CategoryCriteria::create($data);

        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Criterias')->with('success', 'Criteria has been Added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ContestCategoryCriteria  $contestCategoryCriteria
     * @return \Illuminate\Http\Response
     */
    public function show(ContestCategoryCriteria $contestCategoryCriteria)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContestCategoryCriteria  $contestCategoryCriteria
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest, ContestCategory $contestCategory, CategoryCriteria $categoryCriteria)
    {
        $criterias = Criteria::orderBy('name')->get();
        
        return view('category-criterias.edit', compact('contest', 'contestCategory', 'categoryCriteria', 'criterias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContestCategoryCriteria  $contestCategoryCriteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contest $contest, ContestCategory $contestCategory, CategoryCriteria $categoryCriteria)
    {
        $data = request()->validate(
            [
                'criteria_id' => [
                    'required',
                    'numeric',
                    'exists:criterias,id',
                    Rule::unique('category_criterias')->ignore($categoryCriteria)->where('contest_category_id', $contestCategory->id)
                ],
                'percentage' => ['required', 'numeric', 'between:1,100'],
            ],
            [],
            [
                'criteria_id' => 'Criteria',
                'percentage' => 'Percentage',
            ]
        );

        $categoryCriteria->update($data);
        
        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Criterias')->with('success', 'Criteria has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContestCategoryCriteria  $contestCategoryCriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest, ContestCategory $contestCategory, CategoryCriteria $categoryCriteria)
    {
        $categoryCriteria->delete();
        
        return redirect('/contests/' . $contest->id . '/categories/' . $contestCategory->id . '?activeTab=Criterias')->with('success', 'Criteria has been Removed.');
    }
}
