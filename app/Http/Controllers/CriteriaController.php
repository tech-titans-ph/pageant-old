<?php

namespace App\Http\Controllers;

use App\Criteria;
use Illuminate\Http\Request;

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

        $ok = Criteria::create($data);
        if ($ok) {
            session()->flash('ok', 'Criteria has been Created.');
        } else {
            session()->flash('error', 'Criteria was not Created. Something went wrong. Please try again.');
        }

        return redirect('/criterias');
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
        $data = [
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
        ];

        if (request()->name != $criteria->name) {
            array_push($data['name'], 'unique:criterias');
        }

        $ok = $criteria->update(request()->validate($data));
        if ($ok) {
            session()->flash('ok', 'Criteria has been Edited.');
        } else {
            session()->flash('error', 'Criter was not Edited. Something went wrong. Please try again.');
        }

        return redirect('/criterias');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Criteria  $criteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Criteria $criteria)
    {
        $ok = $criteria->delete();
        if ($ok) {
            session()->flash('ok', 'Criteria has been Deleted.');
        } else {
            session()->flash('error', 'Criteria was not Deleted. Something went wrong. Please try again.');
        }
        
        return redirect('/criterias');
    }
}
