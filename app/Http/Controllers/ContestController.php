<?php

namespace App\Http\Controllers;

use App\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $ok = Contest::create($contest);
        if($ok){
            session()->flash('ok', 'Contest has been Created.');
        }else{
            session()->flash('error', 'Contest was not Created. Something went wrong. Please try again.');
        }
        return redirect('/contests');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contest  $contest
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest)
    {
        return 'Contest Details...';
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
            'name' => ['required'],
            'description' => ['required'],
        ];
        if ($contest->name != request()->name) {
            array_push($validationRule['name'], 'unique:contests');
        }
        if(request()->hasFile('logo')){
            $validationRule['logo'] = ['file', 'image'];
        }
        $data = request()->validate($validationRule);
        if(isset($data['logo'])){
            Storage::disk('public')->delete($contest->logo);
            $data['logo'] = request()->logo->store('logos', 'public');
        }
        $ok = $contest->update($data);
        if ($ok) {
            session()->flash('ok', 'Contest has been Edited.');
        } else {
            session()->flash('error', 'Contest was not Edited. Something went wrong. Please try again.');
        }

        return redirect('/contests');
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
        Storage::disk('public')->delete($contest->logo);
        $ok = $contest->delete();
        if ($ok) {
            session()->flash('ok', 'Contest has been Deleted.');
        } else {
            session()->flash('error', 'Contest was not Deleted. Something went wrong. Please try again.');
        }

        return redirect('/contests');
    }

    public function active(Contest $contest)
    {
        session(['activeContest' => $contest]);
        session()->flash('ok', $contest->name . ' has been Activated.');
        return redirect('/contests');
    }
}
