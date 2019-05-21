<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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
        $judges = User::where('role', '=', 'judge')->get();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRule = [
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'picture' => ['required', 'file', 'image'],
            'username' => ['required', 'min:3', 'max:255', 'unique:users'],
            'password' => ['required', 'min:3', 'max:255', 'nospace', 'confirmed'],
        ];
        $judge = request()->validate($validationRule);
        $judge['picture'] = request()->picture->store('profile_pictures', 'public');
        $judge['role'] = 'judge';
        User::create($judge);
        return redirect('/judges');
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
    public function edit(User $user)
    {
        $judge = $user;
        return view('judges.edit', compact('judge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
         $validationRule = [
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'username' => ['required', 'min:3', 'max:255'],
        ];
        if($user->username != request()->username){
            array_push($validationRule['username'], 'unique:users');
        }
        if(request()->hasFile('picture')){
            $validationRule['picture'] = ['file', 'image'];
        }
        $data = request()->validate($validationRule);
        if(isset($data['picture'])){
            Storage::disck('public')->delete($user->picture);
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }
        $user->update($data);
        return redirect('/judges');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Storage::disk('public')->delete($user->picture);
        $user->delete();
        return redirect('/judges');
    }
}
