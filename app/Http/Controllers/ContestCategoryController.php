<?php

namespace App\Http\Controllers;

use App\ContestCategory;
use Illuminate\Http\Request;
use App\Rules\uniqueContestCategory;
use Illuminate\Validation\Rule;

class ContestCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('activeContest');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contestCategories = ContestCategory::whereContestId(session('activeContest')->id)->get();

        return view('contest-categories.index', compact('contestCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contest-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRule = [
            'name' => ['required', 'min:3', 'max:255', new uniqueContestCategory],
            'description' => ['required', 'min:3', 'max:255'],
            'percentage' => ['required', 'numeric', 'between:1,100']
        ];

        $data = request()->validate($validationRule);

        $data['contest_id'] = session('activeContest')->id;

        ContestCategory::create($data);

        return redirect('/contest-categories')->with('success', 'Contest Category has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ContestCategory $contestCategory)
    {
        // TODO: show details
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ContestCategory $contestCategory)
    {
        return view('contest-categories.edit', compact('contestCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContestCategory $contestCategory)
    {
        $validationRule = [
            'name' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('contest_categories')->ignore($contestCategory)->where(function ($query) {
                    return $query->whereContestId(session('activeContest')->id);
                }),
            ],
            'description' => ['required', 'min:3', 'max:255'],
            'percentage' => ['required', 'numeric', 'between:1,100'],
        ];

        $data = request()->validate($validationRule);
        
        $contestCategory->update($data);

        return redirect('/contest-categories')->with('success', 'Contest Category has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContestCategory $contestCategory)
    {
        $contestCategory->delete();

        return redirect('/contest-categories')->with('success', 'Contest Category has been Deleted.');
    }
}
