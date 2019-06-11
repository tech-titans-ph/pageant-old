<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
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
        $categories = Category::all();
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
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
                'name' => ['required', 'min:3', 'max:255', Rule::unique('categories')],
                'description' => ['required', 'min:3', 'max:255'],
            ],
            [],
            [
                'name' => 'Name',
                'description' => 'Description',
            ]
        );

        Category::create($data);

        return redirect('/categories')->with('success', 'Category has been Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = request()->validate(
            [
                'name' => ['required', 'min:3', 'max:255', Rule::unique('categories')->ignore($category)],
                'description' => ['required', 'min:3', 'max:255'],
            ],
            [],
            [
                'name' => 'Name',
                'description' => 'Description',
            ]
        );

        $category->update($data);

        return redirect('/categories')->with('success', 'Category has been Edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->contestCategories->count()) {
            return redirect('/categories')->with('error', 'Could not Delete Category. Please make sure that there is no Contest related with this Category.');
        }

        $category->delete();
        
        return redirect('/categories')->with('success', 'Category has been Deleted.');
    }
}
