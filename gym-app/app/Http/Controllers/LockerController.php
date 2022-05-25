<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\Locker;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LockerController extends Controller
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

        $lockers = Locker::simplePaginate(12);

        return view('admin.lockers.index', ['lockers' => $lockers]);
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

        $gyms = Gym::all();

        return view('admin.lockers.create', ['gyms' => $gyms]);
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
                'gym_id' => 'required|in:' . Gym::all()->pluck('id')->implode(','),
                'number' => [
                    'required',
                    'numeric',
                    'min:1',
                    Rule::unique('lockers')->where(function ($query) use ($request) {
                        return $query->where('gym_id', $request->gym_id);
                    }),
                ],
                'gender' => 'required|in:male,female',
            ],
        );

        $locker = Locker::create($validated);

        return redirect()->route('lockers.index')->with('create', $locker->number);
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

        if (Locker::find($id)->is_used()) {
            abort(403);
        }

        $locker = Locker::find($id);

        if ($locker == null) {
            return redirect()->route('lockers.index')->with('not-found', $id);
        }

        $gyms = Gym::all();

        return view('admin.lockers.edit', ['locker' => $locker, 'gyms' => $gyms]);
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

        $locker = Locker::find($id);

        if ($locker == null) {
            abort(403);
        }

        if ($locker->is_used()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'number' => [
                    'numeric',
                    'min:1',
                    Rule::unique('lockers')->where(function ($query) use ($request) {
                        return $query->where('gym_id', $request->gym_id);
                    })->ignore($locker->id),
                ],
                'gender' => 'in:male,female',
            ],
        );

        $locker->update($validated);

        return redirect()->route('lockers.index')->with('edit', $locker->number);
    }

    public function delete($id)
    {
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        $locker = Locker::find($id);

        if ($locker->is_used()) {
            abort(403);
        }

        return view('admin.lockers.delete', ['locker' => $locker]);
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

        $locker = Locker::find($id);

        if ($locker->is_used()) {
            abort(403);
        }
        $locker_number = $locker->number;
        $locker->delete();

        return redirect()->route('lockers.index')->with('delete', $locker_number);
    }
}
