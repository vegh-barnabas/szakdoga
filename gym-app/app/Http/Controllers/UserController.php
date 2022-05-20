<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
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

        $gym_name = Gym::all()->pluck('name')->implode(',');

        $admins = User::all()->where('is_admin()')->sortBy('name');

        $receptionists = User::all()->where('is_receptionist()')->sortBy('name');

        $users = User::all()->filter(function ($user) {
            return !$user->is_admin() && !$user->is_receptionist();
        })->values()->sortBy('name');

        $all_users = $admins->merge($receptionists);
        $all_users = $all_users->merge($users);

        $gym_count = Gym::all()->count();

        return view('admin.user-list', ['all_users' => $all_users, 'gym_name' => $gym_name, 'gym_count' => $gym_count]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        if (Gym::all()->count() == 0) {
            return Redirect::to('users')->with('no-gym-error', 0);
        }

        $user = User::all()->where('id', $id)->first();

        if ($user == null || $user->is_admin()) {
            abort(403);
        }

        $gyms = Gym::all();

        return view('admin.edit-user', ['user' => $user, 'gyms' => $gyms]);
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

        if (Gym::all()->count() == 0) {
            return Redirect::to('users')->with('no-gym-error');
        }

        $user = User::all()->where('id', $id)->first();

        if ($user == null || $user->is_admin()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'name' => 'required|min:3|max:32',
                'email' => 'required|email:rfc',
                'gender' => 'required|in:male,female',
                'permission' => 'required|in:guest,receptionist',
                'credits' => 'required|integer',
                'exitcode' => 'required|min:6|max:6',
                'gym' => [Rule::requiredIf($user->is_receptionist()), Rule::in(Gym::all()->pluck('id')->implode(','))],
                // 'newpw' => 'min:6|max:32',
                // 'newpw2' => 'same:newpw',
            ],
        );

        if ($validated['permission'] == 'guest') {
            $validated['is_receptionist()'] = 0;
        } else if ($validated['permission'] == 'receptionist') {
            $validated['is_receptionist()'] = 1;
        }

        $user->update($validated);

        return Redirect::back()->with('success', $user->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
