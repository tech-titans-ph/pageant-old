<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judge;
use App\Contest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class JudgeController extends Controller
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
    public function create(Contest $contest)
    {
        return view('judges.create', compact('contest'));
    }

    /**
     * Store a newly Deleted resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contest $contest)
    {
        $data = request()->validate(
            [
                'name' => ['required', 'min:3', 'max:255'],
                'description' => ['required', 'min:3', 'max:255'],
                'picture' => ['required', 'file', 'image'],
            ],
            [],
            [
                'name' => 'Full Name',
                'description' => 'Description',
                'picture' => 'Profile Picture',
            ]
        );

        $data['picture'] = request()->picture->store('profile_pictures', 'public');
        $data['contest_id'] = $contest->id;
        
        Judge::create($data);

        return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('success', 'Judge has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest, Judge $judge)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest, Judge $judge)
    {
        return view('judges.edit', compact('contest', 'judge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contest $contest, Judge $judge)
    {
        $data = request()->validate(
            [
                'name' => ['required', 'min:3', 'max:255'],
                'description' => ['required', 'min:3', 'max:255'],
                'picture' => ['nullable', 'file', 'image'],
            ],
            [],
            [
                'name' => 'Full Name',
                'description' => 'Description',
                'picture' => 'Profile Picture',
            ]
        );

        if (isset($data['picture'])) {
            Storage::disk('public')->delete($judge->picture);
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }

        $judge->update($data);

        return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('success', 'Judge has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contest $contest, Judge $judge)
    {
		if($judge->categories->count()){
			return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('error', 'Could not Delete Judge. Please make sure that it is not yet added in any Category.');
		}

        $judge->delete();
            
        Storage::disk('public')->delete($judge->picture);

        return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('success', 'Judge has been Deleted.');
    }
}
