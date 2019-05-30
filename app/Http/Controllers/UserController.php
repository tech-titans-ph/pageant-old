<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate(
            [
            'name' => ['required', 'max:25'],
            'username' => ['required', 'alpha_dash', 'max:255', 'unique:users'],
            'password' => ['required', 'max:255', 'confirmed'],
            ],
            [],
            [
                'name' => 'Full Name',
                'username' => 'User Name',
                'password' => 'Password',
            ]
        );
        
        $data['password'] = Hash::make($data['password']);
        
        User::create($data);

        return redirect('/users')->with('success', 'User has been Created.');
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
        // return view('users.show', compact($user));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
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
        $data = request()->validate(
            [
                'name' => ['required', 'min:3', 'max:255'],
                'username' => ['required', 'alpha_dash', 'min:3', 'max:255', Rule::unique('users')->ignore($user)],
            ],
            [],
            [
                'name' => 'Full Name',
                'username' => 'User Name',
            ]
        );
        
        $user->update($data);
        
        return redirect('/users')->with('success', 'User has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            session()->flash('error', 'Could not Delete User. Please make sure that the User you are trying to Delete is not you.');
        } else {
            $user->delete();
            session()->flash('success', 'User has been Deleted.');
        }
        
        return redirect('/users');
    }
}
