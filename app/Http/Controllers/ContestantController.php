<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Rules\UniqueContestant;
use Illuminate\Validation\Rule;

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
        $data = request()->validate([
            'first_name' => ['required', 'min:3', 'max:255'],
            'middle_name' => ['required', 'min:3', 'max:255'],
            'last_name' => ['required', 'min:3', 'max:255'],
            'address' => ['required', 'min:3'],
            'picture' => ['required', 'file', 'image'],
            'number' => ['required', 'numeric', new UniqueContestant],
        ]);

        $data['picture'] = request()->picture->store('profile_pictures', 'public');
        $data['contest_id'] = session('activeContest')['id'];

        Contestant::create($data);
        
        return redirect('/contestants')->with('success', 'Contestant has been Created.');
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
        $data = request()->validate([
            'first_name' => ['required', 'min:3', 'max:255'],
            'middle_name' => ['required', 'min:3', 'max:255'],
            'last_name' => ['required', 'min:3', 'max:255'],
            'address' => ['required', 'min:3'],
            'picture' => ['nullable', 'file', 'image'],
            'number' => [
                'required',
                'numeric',
                Rule::unique('contestants')->ignore($contestant)->where(function ($query) {
                    return $query->whereContestId(session('activeContest')->id);
                }),
            ],
        ]);

        if (isset($data['picture'])) {
            Storage::disk('public')->delete($contestant->picture);
            $data['picture'] = request()->picture->store('profile_pictures', 'public');
        }

        $contestant->update($data);

        return redirect('/contestants')->with('success', 'Contestant has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contestant  $contestant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contestant $contestant)
    {
        $contestant->delete();
        Storage::disk('public')->delete($contestant->picture);

        return redirect('/contestants')->with('success', 'Contestant has been Deleted.');
    }
}
