<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class GymController extends Controller
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

        $gyms = Gym::all();

        return view('admin.gym.gym-list', ['gyms' => $gyms]);
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

        $categories = Category::all();

        return view('admin.add-gym', ['categories' => $categories]);
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
                'name' => 'required|min:4|max:32|not_in:' . Gym::all()->pluck('name'),
                'address' => 'required|min:4|max:128',
                'description' => 'required|min:6|max:128',
                'categories' => 'nullable',
                'categories.*' => 'integer|distinct|in:' . Category::all()->pluck('id'),
            ],
        );

        $gym = Gym::create($validated);

        foreach ($request->categories as $category_id) {
            $category = Category::all()->where('id', $category_id)->first();

            $category->gyms()->attach($gym->id);
        }

        return Redirect::back()->with('success', $validated['name']);
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

        $gym = Gym::all()->where('id', $id)->first();

        if ($gym == null) {
            abort(403);
        }

        $categories = Category::all();

        return view('admin.edit-gym', ['gym' => $gym, 'categories' => $categories]);

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

        $gym = Gym::all()->where('id', $id)->first();

        if ($gym == null) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'name' => 'required|min:3|max:120',
                'address' => 'required|min:3|max:120',
                'description' => 'nullable|max:220',
                'categories' => 'nullable',
                'categories.*' => 'integer|distinct|exists:categories,id',
            ],
        );

        $gym->update($validated);

        $gym->categories()->detach();
        $gym->categories()->attach($request->categories);

        return Redirect::back()->with('success', $gym->name);
    }

    public function delete($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $gym = Gym::all()->where('id', $id)->first();

        if ($gym == null) {
            abort(403);
        }

        return view('admin.delete-gym', ['gym' => $gym]);
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

        $gym = Gym::all()->where('id', $id)->first();

        $gym_name = $gym->name;

        if ($gym == null) {
            abort(403);
        }

        $deleted = $gym->delete();
        if (!$deleted) {
            return abort(500);
        }

        return Redirect::to('gyms')->with('deleted', $gym_name);
    }
}
