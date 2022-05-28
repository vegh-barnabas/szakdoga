<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

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

        $all_users = User::orderBy('permission')->orderBy('name')->simplePaginate(15);

        return view('admin.users.index', ['all_users' => $all_users]);
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

        if (Gym::all()->count() == 0) {
            return redirect()->to('users')->with('no-gym-error', 0);
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
        if (!Gate::allows('admin-action')) {
            abort(403);
        }

        if (Gym::all()->count() == 0) {
            return redirect()->to('users')->with('no-gym-error');
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
                    Rule::exists('gyms', 'id'),
                ],
            ],
        );

        $user->update($validated);

        return redirect()->route('users.index')->with('edit', $user->name);
    }
}
