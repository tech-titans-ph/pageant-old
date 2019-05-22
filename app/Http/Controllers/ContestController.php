<?php

namespace App\Http\Controllers;

use App\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContestController extends Controller
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
        $contests = Contest::all();

        return view('contests.index', compact('contests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contest = request()->validate([
            'name' => ['required', 'unique:contests'],
            'description' => ['required'],
            'logo' => ['required', 'file', 'image'],
        ]);

        $contest['logo'] = request()->logo->store('logos', 'public');

        Contest::create($contest);

        return redirect('/contests')->with('success', 'Contest has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest)
    {
        // TODO: show details
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest)
    {
        return view('contests.edit', compact('contest'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contest $contest)
    {
        $validationRule = [
            'name' => ['required', 'min:3', 'max:255', Rule::unique('contests')->ignore($contest)],
            'description' => ['required', 'min:3', 'max:255'],
        ];

        if (request()->hasFile('logo')) {
            $validationRule['logo'] = ['file', 'image'];
        }

        $data = request()->validate($validationRule);

        if (isset($data['logo'])) {
            Storage::disk('public')->delete($contest->logo);
            $data['logo'] = request()->logo->store('logos', 'public');
        }

        $contest->update($data);

        return redirect('/contests')->with('success', 'Contest has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest)
    {
        // TODO: validations
        $contest->delete();

        session()->forget('activeContest');

        Storage::disk('public')->delete($contest->logo);

        return redirect('/contests')->with('success', 'Contest has been Deleted.');
    }

    public function active(Contest $contest)
    {
        session(['activeContest' => $contest]);

        return redirect('/contests')->with('success', $contest->name . ' has been Activated.');
    }
}
