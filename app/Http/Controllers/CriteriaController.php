<?php

namespace App\Http\Controllers;

use App\Criteria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CriteriaController extends Controller
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
        $criterias = Criteria::all();

        return view('criterias.index', compact('criterias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('criterias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'name' => ['required', 'min:3', 'max:255', 'unique:criterias'],
            'description' => ['required', 'min:3', 'max:255'],
        ]);

        Criteria::create($data);

        return redirect('/criterias')->with('success', 'Criteria has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function show(Criteria $criteria)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function edit(Criteria $criteria)
    {
        return view('criterias.edit', compact('criteria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Criteria $criteria)
    {
        $data = request()->validate([
            'name' => ['required', 'min:3', 'max:255', Rule::unique('criterias')->ignore($criteria)],
            'description' => ['required', 'min:3', 'max:255'],
        ]);

        $criteria->update($data);

        return redirect('/criterias')->with('success', 'Criteria has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Criteria $criteria)
    {
        $criteria->delete();
        
        return redirect('/criterias')->with('success', 'Criteria has been Deleted.');
    }
}
