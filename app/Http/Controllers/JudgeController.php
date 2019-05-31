<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Contest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class JudgeController extends Controller
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
            return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('error', 'Could not Create a New Judge. Please make sure that there is no Category that is already Started Scoring or Finished Scoring.');
        }

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
        
        $data['password'] = Hash::make('password');
        $data['picture'] = request()->picture->store('profile_pictures', 'public');
        $data['contest_id'] = $contest->id;
        $data['username'] = $data['name'];
        $data['role'] = 'judge';

        User::create($data);
        
        return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('success', 'Judge has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest, User $judge)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest, User $judge)
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
    public function update(Request $request, Contest $contest, User $judge)
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
    public function destroy(Contest $contest, User $judge)
    {
        if ($judge->categories->count()) {
            return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('error', 'Could not Delete Judge. Please make sure that it is not yet added in any Category.');
        }

        $judge->delete();
            
        Storage::disk('public')->delete($judge->picture);

        return redirect('/contests/' . $contest->id . '?activeTab=Judges')->with('success', 'Judge has been Deleted.');
    }
    
}
