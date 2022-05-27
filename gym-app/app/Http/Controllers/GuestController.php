<?php

namespace App\Http\Controllers;

use App\Models\BuyableTicket;
use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class GuestController extends Controller
{
    public function choose_gym_page()
    {
        if (Gate::allows('admin-action')) {
            return redirect()->route('index');
        }

        if (Gate::allows('receptionist-action')) {
            return redirect()->route('index');
        }

        if (session('gym') != null) {
            return redirect()->route('index');
        }

        // if (Auth::user()->prefered_gym != null) {
        //     session(['gym' => Auth::user()->prefered_gym]);
        //     return redirect()->route('index');
        // }

        return view('gyms.index', ['gyms' => Gym::all()]);
    }

    public function choose_gym(Request $request)
    {
        if (Gate::allows('admin-action') || Gate::allows('receptionist-action')) {
            return redirect()->route('index');
        }

        if (session('gym') != null) {
            return redirect()->route('index');
        }

        $validated = $request->validate([
            'gymId' => [
                'required',
                'numeric',
                Rule::in(Gym::all()->pluck('id')),
            ],
        ]);

        session(['gym' => $validated['gymId']]);

        return redirect()->route('index');
    }

    public function tickets()
    {
        if (Gate::allows('admin-action') || Gate::allows('receptionist-action')) {
            abort(403);
        }

        if (session('gym') == null) {
            return redirect()->route('guest.gyms.list');
        }

        $gym = Gym::find(session('gym'));

        // $tickets = Auth::user()->tickets()
        //     ->join('buyable_tickets', 'buyable_tickets.id', '=', 'tickets.type_id')
        //     ->orderBy('type_str')
        //     ->orderBy('tickets.expiration', 'DESC')
        //     ->paginate(1);

        $tickets = Auth::user()->tickets
            ->where('gym_id', $gym->id)
            ->sortByDesc('expiration')
            ->sortBy(function ($ticket) {
                return $ticket->get_type();
            });

        // $tickets = Ticket::with('type')
        //     ->where('gym_id', $gym->id)
        //     ->whereIn('id', Auth::user()->tickets)
        //     ->orderBy('expiration', 'desc')
        //     ->orderBy('type')
        //     ->paginate(2);

        return view('user.tickets', ['gym' => $gym, 'tickets' => $tickets, 'showPagination' => is_null(request('all'))]);
    }

    public function buy_ticket_list()
    {
        if (Gate::allows('admin-action')) {
            abort(403);
        }

        if (Gate::allows('receptionist-action')) {
            abort(403);
        }

        $gym = Gym::find(session('gym'));

        $buyable_tickets = BuyableTicket::all()->where('gym_id', $gym->id)->where('hidden', false);

        return view('user.buy', ['gym' => $gym, 'buyable_tickets' => $buyable_tickets]);
    }

    public function buy_ticket_show($buyable_ticket_id)
    {
        if (Gate::allows('admin-action') || Gate::allows('receptionist-action')) {
            abort(403);
        }

        if (session('gym') == null) {
            return redirect()->route('guest.gyms.list');
        }

        $ticket = BuyableTicket::find($buyable_ticket_id);

        if ($ticket == null) {
            abort(403);
        }

        if ($ticket->hidden) {
            return redirect()->route('guest.buy-ticket');
        }

        if ($ticket->price > Auth::user()->credits) {
            return redirect()->route('guest.buy-ticket')->with('error', 'error-not-enough-credits');
        }

        $ticket_type = $ticket->isMonthly() ? "Bérlet" : "Jegy";

        $gym = Gym::all()->where('id', $ticket->gym_id)->first();

        return view('user.buy_ticket', ['ticket' => $ticket, 'ticket_type' => $ticket_type, 'gym' => $gym]);
    }

    public function buy_ticket_create($buyable_ticket_id)
    {
        if (Gate::allows('admin-action') || Gate::allows('receptionist-action')) {
            abort(403);
        }

        $buyable_ticket = BuyableTicket::find($buyable_ticket_id);

        if ($buyable_ticket->hidden) {
            abort(403);
        }

        if ($buyable_ticket->price > Auth::user()->credits) {
            abort(403);
        }

        $current_date = Carbon::now()->format('Y-m-d');
        $expiration = Carbon::now()->add('1', 'month')->format('Y-m-d');

        Auth::user()->credits -= $buyable_ticket->price;
        Auth::user()->save();

        Ticket::factory()->create([
            'user_id' => Auth::user()->id,
            'gym_id' => $buyable_ticket->gym_id,
            'buyable_ticket_id' => $buyable_ticket->id,
            'type' => $buyable_ticket->type,
            'bought' => $current_date,
            'expiration' => $expiration,
        ]);

        if ($buyable_ticket->quantity != 999) {
            $buyable_ticket->quantity -= 1;

            if ($buyable_ticket->quantity == 0) {
                $buyable_ticket->hidden = true;
            }

            $buyable_ticket->save();
        }

        return redirect()->route('index');
    }

    public function extend_ticket_page($id)
    {
        if (Gate::allows('admin-action') || Gate::allows('receptionist-action')) {
            return redirect()->route('index');
        }

        if (session('gym') == null) {
            return redirect()->route('guest.gyms.list');
        }

        if (Auth::user()->tickets->where('id', $id)->count() == 0) {
            return redirect()->route('index');
        }

        $ticket = Ticket::all()->where('id', $id)->first();

        if ($ticket == null) {
            abort(403);
        }

        if (!$ticket->expired() || !$ticket->isMonthly()) {
            return redirect()->route('index');
        }

        if (Auth::user()->credits < $ticket->buyable_ticket->price) {
            return redirect()->route('guest.tickets')->with('error-not-enough-credits', ['ticket-name' => $ticket->buyable_ticket->name]);
        }

        $gym = Gym::all()->where('id', $ticket->gym->id)->first();

        return view('user.extend_ticket', ['ticket' => $ticket, 'gym' => $gym]);
    }

    public function extend_ticket($id)
    {
        if (Gate::allows('admin-action') || Gate::allows('receptionist-action')) {
            abort(403);
        }

        if (session('gym') == null) {
            return redirect()->route('guest.gyms.list');
        }

        if (Auth::user()->tickets->where('id', $id)->count() == 0) {
            return redirect()->route('index');
        }

        $ticket = Ticket::all()->where('id', $id)->first();

        if ($ticket->useable()) {
            return redirect()->route('tickets');
        }

        if (Auth::user()->credits < $ticket->buyable_ticket->price) {
            abort(403);
        }

        $expiration = Carbon::now();
        $expiration->add(1, 'month');

        $ticket->expiration = $expiration;
        $ticket->save();

        $user = User::all()->where('id', Auth::user()->id)->first();
        $user->credits -= $ticket->buyable_ticket->price;
        $user->save();

        return redirect()->route('index');
    }

    public function statistics()
    {
        if (Gate::allows('admin-action') || Gate::allows('receptionist-action')) {
            abort(403);
        }

        $gym = Gym::find(session('gym'));

        // $enterances = Enterance::all()->where('gym_id', $gym->id);

        // $enterance_counts = [];
        // for ($i = 0; $i < 24; $i++) {
        //     $enterance_counts[$i] = 0;
        // }

        // for ($i = 0; $i < 24; $i++) {
        //     $date = Carbon::today()->add($i, 'hour');

        //     foreach ($enterances as $enterance) {
        //         $enter = Carbon::create($enterance->enter);
        //         if ($enter->diffInHours($date) == 0) {
        //             $enterance_counts[$i]++;
        //         }

        //         $exit = Carbon::create($enterance->exit);
        //         if ($exit->diffInHours($date) == 0) {
        //             $enterance_counts[$i]++;
        //         }
        //     }
        // }

        // TODO: make this into a function in model
        // TODO: dont show table if the user was never in the gym
        // Enterance avg
        $min_sum = 0;
        $count = 0;
        foreach (Auth::user()->enterances as $enterance) {
            if ($enterance->gym_id == $gym->id) {
                $start = new DateTime($enterance->enter);
                $end = new DateTime($enterance->exit);

                $mins = ($end->getTimestamp() - $start->getTimestamp()) / 60;
                $min_sum += $mins;

                $count++;
            }
        }

        if ($count != 0) {
            $min_avg = $min_sum / $count;
        } else {
            $min_avg = 0;
        }

        $hours = intdiv($min_avg, 60);
        $minutes = $min_avg % 60;

        // Statistics
        $enterances = Enterance::all()->where('gym_id', $gym->id);
        $date_today = Carbon::now()->format('Y-m-d');
        $enterance_count = 0;

        foreach ($enterances as $enterance) {
            $enterance_enter = Carbon::create($enterance->enter)->format('Y-m-d');
            if ($enterance_enter == $date_today) {
                $enterance_count++;
            }
        }

        return view('user.statistics',
            ['gym' => $gym, 'hour_avg' => $hours, 'min_avg' => $minutes, 'enterance_count' => $enterance_count]);
    }

    public function settings_page()
    {
        if (Gate::allows('receptionist-action')) {
            abort(403);
        } else if (Gate::allows('admin-action')) {
            abort(403);
        }

        $gyms = Gym::all();
        $selected_gym_id = Gym::find(session('gym'))->id;
        $current_gym = session('gym');

        return view('user.settings', ['gyms' => $gyms, 'selected_gym_id' => $selected_gym_id, 'current_gym' => $current_gym]);
    }

    public function settings(Request $request)
    {
        $user = User::all()->where('id', Auth::user()->id)->first();
        // error_log(json_encode($user));

        $gyms = Gym::all();

        $validated = $request->validate(
            [
                'current' => [
                    Rule::in($gyms->pluck('id')),
                ],
                'prefered' => [
                    Rule::in($gyms->pluck('id')->push('none')),
                ],
            ]
        );

        session(['gym' => $validated['current']]);

        if ($request['prefered'] !== 'none') {
            $user->prefered_gym = $request['prefered'];
        } else {
            $user->prefered_gym = null;
        }

        $user->save();

        return redirect()->route('guest.settings')->with('success', 'success');
    }
}
