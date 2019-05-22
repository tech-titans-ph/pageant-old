<?php

namespace App\Http\Controllers;

use App\ContestCategory;
use Illuminate\Http\Request;
use App\Rules\uniqueContestCategory;

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

        $ok = ContestCategory::create($data);
        if ($ok) {
            session()->flash('ok', 'Contest Category has been Created.');
        } else {
            session()->flash('error', 'Contest Category was not Created. Something went wrong. Please try again.');
        }

        return redirect('/contest-categories');
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
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'percentage' => ['required', 'numeric', 'between:1,100'],
        ];

        if ($contestCategory->name != request()->name) {
            array_push($validationRule['name'], new uniqueContestCategory);
        }

        $data = request()->validate($validationRule);
        
        $ok = $contestCategory->update($data);
        if ($ok) {
            session()->flash('ok', 'Contest Category has been Edited.');
        } else {
            session()->flash('error', 'Contest Category was not Edited. Something went wrong. Please try again.');
        }

        return redirect('/contest-categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContestCategory $contestCategory)
    {
        $ok = $contestCategory->delete();
        if ($ok) {
            session()->flash('ok', 'Contest Category has been Deleted.');
        } else {
            session()->flash('error', 'Contest Category was not Deleted. Something went wrong. Please try again.');
        }

        return redirect('/contest-categories');
    }
}
