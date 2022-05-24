<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

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

        $gym_name = Gym::all()->pluck('name')->implode(', ');

        $admins = User::all()->filter(function ($user) {
            return $user->is_admin();
        })->sortBy('name');

        $receptionists = User::all()->filter(function ($user) {
            return $user->is_receptionist();
        })->sortBy('name');

        $users = User::all()->filter(function ($user) {
            return $user->is_guest();
        })->values()->sortBy('name');

        $all_users = $admins->merge($receptionists);
        $all_users = $all_users->merge($users);

        $gym_count = Gym::all()->count();

        return view('admin.users.index', ['all_users' => $all_users, 'gym_name' => $gym_name, 'gym_count' => $gym_count]);
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

        return view('admin.users.edit', ['user' => $user, 'gyms' => $gyms]);
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
                'name' => [
                    'min:3',
                    'max:32',
                    Rule::unique('users', 'name')->ignore($user->id),
                ],
                'email' => [
                    'email:rfc',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'gender' => 'in:male,female',
                'permission' => 'in:user,receptionist',
                'credits' => [
                    Rule::requiredIf(!$user->is_receptionist()),
                    'integer',
                ],
                'exit_code' => [
                    Rule::requiredIf(!$user->is_receptionist()),
                    'min:6',
                    'max:6',
                ],
                'prefered_gym' => [
                    Rule::requiredIf($user->is_receptionist()),
                    'in:' . Gym::all()->pluck('id')->implode(','),
                ],
            ],
        );

        $user->update($validated);

        return redirect()->route('users.index')->with('edit', $user->name);
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
