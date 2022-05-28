<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class GymController extends Controller
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

        $gyms = Gym::simplePaginate(8);

        return view('admin.gyms.index', ['gyms' => $gyms]);
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

        $categories = Category::all();

        return view('admin.gyms.create', ['categories' => $categories]);
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
                    'min:4',
                    'max:32',
                    Rule::unique('gyms', 'name'),
                ],
                'address' => [
                    'required',
                    'min:4',
                    'max:128',
                ],
                'description' => [
                    'required',
                    'min:6',
                    'max:128',
                ],
                'categories' => [
                    'nullable',
                ],
                'categories.*' => [
                    'integer',
                    'distinct',
                    Rule::exists('categories', 'id'),
                ],
            ]
        );

        $gym = Gym::create($validated);

        if ($request->categories != null) {
            foreach ($request->categories as $category_id) {
                $category = Category::all()->where('id', $category_id)->first();

                $category->gyms()->attach($gym->id);
            }
        }

        return redirect()->route('gyms.index')->with('create', $gym->name);
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

        $gym = Gym::all()->where('id', $id)->first();

        if ($gym == null) {
            abort(403);
        }

        $categories = Category::all();

        return view('admin.gyms.edit', ['gym' => $gym, 'categories' => $categories]);
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

        $gym = Gym::all()->where('id', $id)->first();

        if ($gym == null) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'min:4',
                    'max:32',
                    Rule::unique('gyms', 'name')->ignore($gym->id),
                ],
                'address' => [
                    'required',
                    'min:4',
                    'max:128',
                ],
                'description' => [
                    'required',
                    'min:6',
                    'max:128',
                ],
                'categories' => [
                    'nullable',
                ],
                'categories.*' => [
                    'integer',
                    'distinct',
                    Rule::exists('categories', 'id'),
                ],
            ]
        );

        $gym->update($validated);

        $gym->categories()->detach();
        $gym->categories()->attach($request->categories);

        return redirect()->route('gyms.index')->with('edit', $gym->name);
    }

    public function delete($id)
    {
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $gym = Gym::all()->where('id', $id)->first();

        if ($gym == null) {
            abort(403);
        }

        return view('admin.gyms.delete', ['gym' => $gym]);
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

        $gym = Gym::all()->where('id', $id)->first();

        $gym_name = $gym->name;

        if ($gym == null) {
            abort(403);
        }

        $deleted = $gym->delete();
        if (!$deleted) {
            return abort(500);
        }

        return redirect()->route('gyms.index')->with('delete', $gym_name);
    }
}
