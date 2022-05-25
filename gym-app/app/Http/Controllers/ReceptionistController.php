<?php

namespace App\Http\Controllers;

use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Locker;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class ReceptionistController extends Controller
{
    public function let_in_index_page()
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        error_log(json_encode(session('gym')));

        $gym = Gym::find(session('gym'));

        return view('receptionist.let-in', ['gym' => $gym]);
    }

    public function let_in_index(Request $request)
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'enterance_code' => [
                    'required',
                ],
            ],
        );
        $code = $validated['enterance_code'];

        $ticket = Ticket::all()->where('code', $code)->first();

        if ($ticket == null) {
            return Redirect::back()->with('not-found', ['code' => $code]);
        }

        if (!$ticket->isMonthly() && !$ticket->enterances->isEmpty()) {
            $enterance_date = CarbonImmutable::create($ticket->enterances->first()->enter);

            return Redirect::to('let-in')
                ->with('used-ticket', ['ticket' => $code, 'used' => $enterance_date->format('Y. m. d. H:i')]);
        }

        $user = $ticket->user;
        if ($user->enterances->where('exit', null)->count() > 0) {
            return Redirect::back()->with('error', ['code' => $code, 'user' => $user->name]);
        }

        if ($ticket->buyable_ticket->gym_id != Gym::find(session('gym'))->id) {
            return Redirect::back()->with('not-this-gym', ['code' => $code]);
        }

        return redirect()->route('receptionist.let-in.page', $code);
    }

    public function let_in_page($code)
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $ticket = Ticket::all()->where('code', $code)->first();

        if ($ticket == null) {
            return view('receptionist.let-in');
        }

        if (!$ticket->isMonthly() && !$ticket->enterances->isEmpty()) {
            return view('receptionist.let-in');
        }

        $still_entered_enterances = Enterance::all()->where('ticket_id', $ticket->id)->filter(function ($enterance) {
            return $enterance->exit != null;
        })->values();

        if (!$still_entered_enterances->isEmpty()) {
            return view('receptionist.let-in');
        }

        $user = $ticket->user;

        $gym = Gym::all()->where('id', $ticket->buyable_ticket->gym_id)->first();

        $lockers = Locker::all()->where('gym_id', $gym->id)->where('gender', $ticket->user->gender)->filter(function ($locker) {
            return !$locker->is_used();
        });

        return view('receptionist.let-in-2', ['user' => $user, 'ticket' => $ticket, 'code' => $code, 'gym' => $gym, 'lockers' => $lockers]);

    }

    public function let_in(Request $request, $code)
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $ticket = Ticket::all()->where('code', $code)->first();

        if (!$ticket->isMonthly() && !$ticket->enterances->isEmpty()) {
            abort(403);
        }

        $still_entered_enterances = Enterance::all()->where('ticket_id', $ticket->id)->filter(function ($enterance) {
            return $enterance->exit != null;
        })->values();

        if (!$still_entered_enterances->isEmpty()) {
            abort(403);
        }

        $free_lockers = Locker::all()
            ->where('gym_id', $ticket->gym->id)
            ->where('gender', $ticket->user->gender)
            ->filter(function ($locker) {
                return !$locker->is_used();
            })
            ->pluck('id')->implode(',');

        $request->validate(
            // Validation rules
            [
                'locker' => [
                    'required',
                    'in:' . $free_lockers,
                ],
                'keyGiven' => [
                    'required',
                    'accepted',
                ],
            ],
        );

        $user = $ticket->user;

        $locker = Locker::all()->where('id', $request['locker'])->first();

        $enterance = Enterance::factory()->create([
            'gym_id' => $ticket->buyable_ticket->gym_id,
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'enter' => Carbon::now(),
            'exit' => null,
        ]);

        $locker->enterance_id = $enterance->id;
        $locker->save();

        $enterance->locker_id = $locker->id;
        $enterance->save();

        return Redirect::to('let-in')->with('success', $user->name);
    }

    public function let_out_index_page()
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $gym = Gym::find(session('gym'));

        return view('receptionist.let-out', ['gym' => $gym]);
    }

    public function let_out_index(Request $request)
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $validated = $request->validate(
            [
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
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $user = User::all()->where('exit_code', $code)->first();
        if ($user == null) {
            return Redirect::to('let-out')->with('error-not-found', $code);
        }

        $enterance = $user->enterances->where('exit', null)->first();
        if ($enterance == null) {
            return Redirect::to('let-out')->with('error', ['code' => $user->exit_code, 'user' => $user->name]);
        }

        $gym = Gym::all()->where('id', $enterance->gym_id)->first();

        return view('receptionist.let-out-2', ['user' => $user, 'enterance' => $enterance, 'gym' => $gym]);
    }

    public function let_out(Request $request, $code)
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

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

        $enterance->locker->enterance_id = null;
        $enterance->locker->save();

        $enterance->locker_id = null;
        $enterance->save();

        return Redirect::to('let-out')->with('success', $user->name);
    }

    public function entered_users()
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $gym = Gym::find(session('gym'));
        $enterances = Enterance::all()->where('gym_id', $gym->id)->where('exit', null);

        return view('receptionist.entered-users', ['gym_name' => $gym->name, 'enterances' => $enterances]);
    }

    public function add_credits_index()
    {
        $gym = Gym::find(session('gym'));

        return view('receptionist.add-credits', ['gym' => $gym]);
    }

    public function add_credits(Request $request)
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $request->validate(
            [
                'name' => [
                    'required',
                    'in:' . User::all()->pluck('name'),
                ],
                'amount' => [
                    'required',
                    'integer',
                    'min:1',
                ],
                'money_recieved' => [
                    'required',
                    'accepted',
                ],
            ],
        );

        $user = User::all()->where('name', $request['name'])->first();
        $user->credits += $request['amount'];
        $user->save();

        return Redirect::to('add-credits')->with('success', ['name' => $user->name, 'amount' => $request['amount']]);
    }

    public function sensitive_settings(Request $request)
    {
        if (!Gate::allows('receptionist-action')) {
            abort(403);
        }

        $user = User::all()->where('id', Auth::user()->id)->first();

        $request->validate(
            [
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'password' => [
                    'nullable',
                    'min:8',
                    'max:32',
                    'confirmed',
                ],
                'current_password' => [
                    'required',
                    'current_password',
                ],
            ]
        );

        if ($request['email'] != null) {
            $user->email = $request['email'];
        }
        if ($request['password'] != null) {
            $user->password = Hash::make($request['password']);
        }

        $user->save();

        return redirect()->route('sensitive-settings')->with('success', 'success');
    }
}
