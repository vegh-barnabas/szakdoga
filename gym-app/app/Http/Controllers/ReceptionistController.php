<?php

namespace App\Http\Controllers;

use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ReceptionistController extends Controller
{
    public function let_in_index_page()
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $gym = Gym::find(session('gym'));

        return view('receptionist.let-in', ['gym' => $gym]);
    }

    public function let_in_index(Request $request)
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                // TODO: receptionist can only let in people in in the same gym
                'enterance_code' => [
                    'required',
                ],
            ],
        );
        $code = $validated['enterance_code'];

        error_log($code);

        $ticket = Ticket::all()->where('code', $code)->first();

        if ($ticket == null) {
            return Redirect::back()->with('not-found', ['code' => $code]);
        }

        $user = $ticket->user;
        if ($user->enterances->where('exit', null)->count() > 0) {
            return Redirect::back()->with('error', ['code' => $code, 'user' => $user->name]);
        }

        if ($ticket->gym_id != Gym::find(session('gym'))->id) {
            return Redirect::back()->with('not-this-gym', ['code' => $code]);
        }

        return redirect()->route('receptionist.let-in.page', $code);
    }

    public function let_in_page($code)
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $ticket = Ticket::all()->where('code', $code)->first();

        if ($ticket == null) {
            return view('receptionist.let-in');
        }
        // TODO: error message
        if ($ticket->exit !== null) {
            return view('receptionist.let-in');
        }
        // TODO: error message

        $user = $ticket->user;

        $gym = Gym::all()->where('id', $ticket->gym_id)->first();

        return view('receptionist.let-in-2', ['user' => $user, 'ticket' => $ticket, 'code' => $code, 'gym' => $gym]);

    }

    public function let_in(Request $request, $code)
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $ticket = Ticket::all()->where('code', $code)->first();
        $user = $ticket->user;

        $validated = $request->validate(
            // Validation rules
            [
                'keyGiven' => [
                    'required',
                    'accepted',
                ],
            ],
        );

        $enter_time = new DateTime();
        $new_enterance = Enterance::factory()->create([
            'gym_id' => $ticket->gym_id,
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'enter' => $enter_time,
            'exit' => null,
        ]);

        return Redirect::to('let-in')->with('success', $user->name);
    }

    public function let_out_index_page()
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $gym = Gym::find(session('gym'));

        return view('receptionist.let-out', ['gym' => $gym]);
    }

    public function let_out_index(Request $request)
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                // TODO: receptionist can only let in people out in the same gym
                'exit_code' => [
                    'required',
                ],
            ],
        );
        $exit_code = $validated['exit_code'];

        $user = User::all()->where('exit_code', $exit_code)->first();

        if ($user == null) {
            return Redirect::back()->with('not-found', $exit_code);
        }
        if ($user->enterances->where('exit', null)->count() == 0) {
            return Redirect::back()->with('error', ['code' => $user->exit_code, 'user' => $user->name]);
        }

        $enterance = Enterance::all()->where('user_id', $user->id)->where('exit', null)->first();

        if ($enterance->gym_id != Gym::find(session('gym'))->id) {
            return Redirect::back()->with('not-this-gym', ['code' => $exit_code]);
        }

        return redirect()->route('receptionist.let-out.page', $user->exit_code);
    }

    public function let_out_page($code)
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $user = User::all()->where('exit_code', $code)->first(); // TODO: is $code safe to use?
        if ($user == null) {
            return Redirect::to('let-out')->with('error-not-found', $code);
        }

        $enterance = $user->enterances->where('exit', null)->first();
        if ($enterance == null) {
            return Redirect::to('let-out')->with('error', ['code' => $user->exit_code, 'user' => $user->name]);
        } // TODO: rename this exit code from 'error'

        $gym = Gym::all()->where('id', $enterance->gym_id)->first();

        return view('receptionist.let-out-2', ['user' => $user, 'enterance' => $enterance, 'gym' => $gym]);
    }

    public function let_out(Request $request, $code)
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        error_log($code);

        $user = User::all()->where('exit_code', $code)->first();
        if ($user == null) {
            abort(403);
        }

        $request->validate(
            [
                'keyGiven' => [
                    'required',
                    'accepted',
                ],
            ],
        );

        $enterance = $user->enterances->where('exit', null)->first();

        $date_now = new DateTime();
        $enterance->exit = $date_now;
        $enterance->save();

        return Redirect::to('let-out')->with('success', $user->name);
    }

    public function entered_users()
    {
        if (!Auth::user()->is_receptionist()) {
            abort(403);
        }

        $gym = Gym::find(session('gym'));
        $enterances = Enterance::all()->where('gym_id', $gym->id)->where('exit', null);

        return view('receptionist.entered-users', ['gym_name' => $gym->name, 'enterances' => $enterances]);
    }
}
