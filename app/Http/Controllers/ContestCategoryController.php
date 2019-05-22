<?php

namespace App\Http\Controllers;

use App\ContestCategory;
use Illuminate\Http\Request;

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
            'name' => ['required', 'min:3', 'max:255', function($attribute, $value, $fail){
                $found = ContestCategory::whereContestId(session('activeContest')->id)
                    ->whereName(request()->name)
                    ->first() ? true : false;
                if($found){
                    $fail('The Name is already taken.');
                }
            }],
            'description' => ['required', 'min:3', 'max:255'],
        ];
        $data = request()->validate($validationRule);
        ContestCategory::create($data);
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
        abor(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContestCategory  $contestCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ContestCategory $contestCategory)
    {
        return view('contest-categories.edit', compact($contestCategory));
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
        ];
        if($contestCategory->name != request()->name){
            array_push($validationRule['name'], function ($attribute, $value, $fail) {
                $found = ContestCategory::whereContestId(session('activeContest')->id)
                    ->whereName(request()->name)
                    ->first() ? true : false;
                if ($found) {
                    $fail('The Name is already taken.');
                }
            });
        }
        $data = request()->validate($validationRule);
        $contestCategory::update($data);
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
        $contestCategory->delete();
        return redirect('/contest-categories');
    }
}
