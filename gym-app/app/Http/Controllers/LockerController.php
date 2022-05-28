<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\Locker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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

        $button_show = true;
        if (Gym::all()->count() == 0) {
            $button_show = false;
        }

        $lockers = Locker::simplePaginate(12);

        return view('admin.lockers.index', ['lockers' => $lockers, 'button_show' => $button_show]);
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

        if ($gyms->count() == 0) {
            abort(403);
        }

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
                'gym_id' => [
                    'required',
                    Rule::exists('gyms', 'id'),
                ],
                'number' => [
                    'required',
                    'numeric',
                    'min:1',
                    Rule::unique('lockers')->where(function ($query) use ($request) {
                        return $query->where('gym_id', $request->gym_id);
                    }),
                ],
                'gender' => [
                    'required',
                    Rule::in(['male', 'female']),
                ],
            ]
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

        $locker = Locker::find($id);

        if ($locker == null) {
            abort(403);
        }

        if ($locker->is_used()) {
            abort(403);
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
                    'required',
                    'numeric',
                    'min:1',
                    Rule::unique('lockers')->where(function ($query) use ($locker) {
                        return $query->where('gym_id', $locker->gym_id);
                    })->ignore($locker->id),
                ],
                'gender' => [
                    'required',
                    Rule::in(['male', 'female']),
                ],
            ]
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
