<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Rules\uniqueContestant;

class ContestantController extends Controller
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
        $contestants = Contestant::whereContestId(session('activeContest')->id)->get();
        return view('contestants.index', compact('contestants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contestants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contestant = request()->validate([
            'first_name' => ['required', 'min:3', 'max:255'],
            'middle_name' => ['required', 'min:3', 'max:255'],
            'last_name' => ['required', 'min:3', 'max:255'],
            'address' => ['required', 'min:3'],
            'picture' => ['required', 'file', 'image'],
            'number' => ['required', 'numeric', new uniqueContestant],
        ]);
        $contestant['picture'] = request()->picture->store('profile_pictures', 'public');
        $contestant['contest_id'] = session('activeContest')['id'];
        $ok = Contestant::create($contestant);
        if($ok){
            session()->flash('ok', 'Contestant has been Created.');
        }else{
            session()->flash('error', 'Contestant was not Created. Something went wrong. Please try again.');
        }
        return redirect('/contestants');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function show(Contestant $contestant)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function edit(Contestant $contestant)
    {
        return view('contestants.edit', compact('contestant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contestant $contestant)
    {
        $validationRule = [
            'first_name' => ['required', 'min:3', 'max:255'],
            'middle_name' => ['required', 'min:3', 'max:255'],
            'last_name' => ['required', 'min:3', 'max:255'],
            'address' => ['required', 'min:3'],
            'number' => ['required', 'numeric'],
        ];
        if(request()->hasFile('picture')){
            $validationRule['picture'] = ['file', 'image'];
        }
        if(request()->number != $contestant->number){
            array_push($validationRule['number'], new uniqueContestant);
        }
        $data = request()->validate($validationRule);
        if(isset($data['picture'])){
            Storage::disk('public')->delete($contestant->picture);
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }
        $ok = $contestant->update($data);
        if ($ok) {
            session()->flash('ok', 'Contestant has been Edited.');
        } else {
            session()->flash('error', 'Contestant was not Edited. Something went wrong. Please try again.');
        }

        return redirect('/contestants');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contestant $contestant)
    {
        Storage::disk('public')->delete($contestant->picture);
        $ok = $contestant->delete();
        if ($ok) {
            session()->flash('ok', 'Contestant has been Deleted.');
        } else {
            session()->flash('error', 'Contestant was not Deleted. Something went wrong. Please try again.');
        }

        return redirect('/contestants');
    }
}
