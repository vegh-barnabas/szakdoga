<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $categories = Category::simplePaginate(8);

        $styles = Category::styles;

        return view('admin.categories.index', ['categories' => $categories, 'styles' => $styles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $styles = Category::styles;

        return view('admin.categories.create', ['styles' => $styles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'min:2',
                    'max:32',
                    Rule::unique('categories', 'name'),
                ],
                'style' => [
                    'required',
                    Rule::in(Category::styles),
                ],
            ]
        );

        $category = Category::create($validated);

        return redirect()->route('categories.index')->with('create', $category->name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $category = Category::all()->where('id', $id)->first();

        if ($category == null) {
            abort(403);
        }

        $styles = Category::styles;

        return view('admin.categories.edit', ['category' => $category, 'styles' => $styles]);
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
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $category = Category::all()->where('id', $id)->first();

        if ($category == null) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'min:2',
                    'max:32',
                    Rule::unique('categories', 'name')->ignore($category->id),
                ],
                'style' => [
                    'required',
                    Rule::in(Category::styles),
                ],
            ]
        );

        $category->update($validated);

        return redirect()->route('categories.index')->with('edit', $category->name);
    }

    public function delete($id)
    {
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $category = Category::all()->where('id', $id)->first();

        if ($category == null) {
            abort(403);
        }

        $styles = Category::styles;

        return view('admin.categories.delete', ['category' => $category, 'styles' => $styles]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('admin-action')) {
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

        return redirect()->route('categories.index')->with('delete', $category_name);
    }
}
