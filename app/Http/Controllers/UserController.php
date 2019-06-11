<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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
		$users = User::all();
		
		$roles = ['admin' => 'Administrator', 'judge' => 'Judge'];

        return view('users.index', compact('users', 'roles'));
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
                'name' => ['required', 'min:3', 'max:255'],
                'username' => ['required', 'alpha_dash', 'max:255', 'unique:users'],
                'password' => ['required', 'max:255', 'confirmed'],
                'role' => ['required'],
                'description' => ['max:255'],
                'picture' => ['nullable', 'file', 'image'],
            ],
            [],
            [
                'name' => 'Full Name',
                'username' => 'User Name',
                'password' => 'Password',
                'role' => 'Role',
                'description' => 'Description',
                'picture' => 'Profile Picture',
            ]
        );
        
        $data['password'] = Hash::make($data['password']);
        
        if (isset($data['picture'])) {
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }
        
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
                'role' => ['required'],
                'description' => ['max:255'],
                'picture' => ['nullable', 'file', 'image'],
            ],
            [],
            [
                'name' => 'Full Name',
                'username' => 'User Name',
                'role' => 'Role',
                'description' => 'Description',
                'picture' => 'Profile Picture'
            ]
        );
        
        if (isset($data['picture'])) {
            Storage::disk('public')->delete($user->picture);
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }

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
		if($user->judges->count()){
			session()->flash('error', 'Could not Delete Judge User. Please make sure that this Judge is not included in Contest.');
			return redirect('/users/' . $user->id . '/edit');
		}
		
        if ($user->id == auth()->id()) {
            session()->flash('error', 'Could not Delete Administrator User. Please make sure that the User you are trying to Delete is not you.');
            return redirect('/users/' . $user->id . '/edit');
        } else {			
			$user->delete();
			
			if($user->picture){
				Storage::disk('public')->delete($user->picture);
			}

            session()->flash('success', 'User has been Deleted.');
        }
        
        return redirect('/users');
    }
}
