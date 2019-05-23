<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JudgeController extends Controller
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
        $judges = User::whereRole('judge')
            ->whereContestId(session('activeContest')->id)
            ->get();

        return view('judges.index', compact('judges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('judges.create');
    }

    /**
     * Store a newly Deleted resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'picture' => ['required', 'file', 'image'],
            'username' => ['required', 'min:3', 'max:255', 'unique:users', 'nospace'],
            'password' => ['required', 'min:3', 'max:255', 'nospace', 'confirmed'],
        ]);

        $data['picture'] = request()->picture->store('profile_pictures', 'public');
        $data['role'] = 'judge';
        $data['contest_id'] = session('activeContest')['id'];
        $data['password'] = Hash::make($data['password']);
        
        User::create($data);

        return redirect('/judges')->with('success', 'Judge has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, $id)
    {
        $judge = User::findOrFail($id);

        return view('judges.edit', compact('judge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, $id)
    {
        $judge = User::findOrFail($id);

        $data = request()->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'username' => ['required', 'min:3', 'max:255', 'nospace', Rule::unique('users')->ignore($judge)],
            'picture' => ['nullable', 'file', 'image'],
        ]);

        if (isset($data['picture'])) {
            Storage::disk('public')->delete($judge->picture);
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }

        $judge->update($data);

        return redirect('/judges')->with('success', 'Judge has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, $id)
    {
        $judge = $user->findOrFail($id);
        
        $judge->delete();
            
        Storage::disk('public')->delete($judge->picture);

        return redirect('/judges')->with('success', 'Judge has been Deleted.');
    }
    
}
