<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $categories = Category::all();

        $styles = Category::styles;

        return view('admin.category.index', ['categories' => $categories, 'styles' => $styles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $styles = Category::styles;

        return view('admin.category.create', ['styles' => $styles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'name' => 'required|min:4|max:32',
                'style' => 'required|in:' . implode(',', Category::styles),
            ],
        );

        $category = Category::create($validated);

        return Redirect::to('admin.category.create')->with('success', $category->name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $category = Category::all()->where('id', $id)->first();

        if ($category == null) {
            abort(403);
        }

        $styles = Category::styles;

        return view('edit', ['category' => $category, 'styles' => $styles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $category = Category::all()->where('id', $id)->first();

        if ($category == null) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'name' => 'required|min:4|max:32',
                'style' => 'required|in:' . implode(',', Category::styles),
            ],
        );

        $category->update($validated);

        return Redirect::to('admin.category.edit')->with('success', $category->name);
    }

    public function delete($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $category = Category::all()->where('id', $id)->first();

        if ($category == null) {
            abort(403);
        }

        $styles = Category::styles;

        return view('admin.category.delete', ['category' => $category, 'styles' => $styles]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $category = Category::all()->where('id', $id)->first();

        $category_name = $category->name;

        if ($category == null) {
            abort(403);
        }

        $deleted = $category->delete();
        if (!$deleted) {
            return abort(500);
        }

        return Redirect::to('admin.category.index')->with('deleted', $category_name);
    }
}
