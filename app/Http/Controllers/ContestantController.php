<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contestant;
use App\Contest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ContestantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware('adminUser');
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
    public function create(Contest $contest)
    {
        if ($contest->categories()->whereIn('status', ['scoring', 'done'])->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Contestants')->with('error', 'Could not Create a New Contestant. Please make sure that there is no Category that is already Started Scoring or Finished Scoring.');
        }

        return view('contestants.create', compact('contest'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contest $contest)
    {
        $data = request()->validate(
            [
                'name' => ['required', 'min:3', 'max:255'],
                'description' => ['required', 'min:3'],
                'number' => ['required', 'numeric', Rule::unique('contestants')->where('contest_id', $contest->id)],
                'picture' => ['required', 'file', 'image'],
            ],
            [],
            [
                'name' => 'Full Name',
                'description' => 'Description',
                'number' => 'Number',
                'picture' => 'Profile Picture',
            ]
        );

        $data['picture'] = request()->picture->store('profile_pictures', 'public');
        $data['contest_id'] = $contest->id;

        Contestant::create($data);
        
        return redirect('/contests/' . $contest->id . '?activeTab=Contestants')->with('success', 'Contestant has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest, Contestant $contestant)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest, Contestant $contestant)
    {
        return view('contestants.edit', compact('contest', 'contestant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contest $contest, Contestant $contestant)
    {
        $data = request()->validate(
            [
                'name' => ['required', 'min:3', 'max:255'],
                'description' => ['required', 'min:3'],
                'number' =>  ['required', 'numeric', Rule::unique('contestants')->ignore($contestant)->where('contest_id', $contest->id)],
                'picture' => ['nullable', 'file', 'image'],
            ],
            [],
            [
                'name' => 'Full Name',
                'description' => 'Description',
                'number' => 'Number',
                'picture' => 'Profile Picture',
            ]
        );

        if (isset($data['picture'])) {
            Storage::disk('public')->delete($contestant->picture);
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }

        $contestant->update($data);

        return redirect('/contests/' . $contest->id . '?activeTab=Contestants')->with('success', 'Contestant has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest, Contestant $contestant)
    {
        if ($contestant->categories->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Contestants')->with('error', 'Could not Delete Contestant. Please make sure that it is not yet added in any Category.');
        }

        $contestant->delete();
        
        Storage::disk('public')->delete($contestant->picture);

        return redirect('/contests/' . $contest->id . '?activeTab=Contestants')->with('success', 'Contestant has been Deleted.');
    }
}
