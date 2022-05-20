<?php

namespace App\Http\Controllers;

use App\Models\BuyableTicket;
use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Ticket;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    public function choose_gym_page()
    {
        if (Auth::user()->is_admin()) {
            return redirect()->route('index');
        } else {
            if (session('gym') != null) {
                return redirect()->route('index');
            }

            if (Auth::user()->prefered_gym != null) {
                session(['gym' => Auth::user()->prefered_gym]);
                return redirect()->route('index');
            }
        }

        return view('gyms.index', ['gyms' => Gym::all()]);
    }

    public function choose_gym()
    {
        if (session('gym') != null) {
            return redirect()->route('index');
        }

        // TODO: check if this validation redicect is working
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
        if (session('gym') == null) {
            return redirect()->route('gyms');
        }

        if (Auth::user()->is_receptionist()) {
            return redirect()->route('home');
        }

        $gym = Gym::find(session('gym'));

        // TODO: paginaion
        $tickets = Auth::user()->tickets
            ->where('gym_id', $gym->id)
            ->sortByDesc('expiration')
            ->sortBy(function ($ticket) {
                return $ticket->get_type();
            });

        return view('user.tickets', ['gym' => $gym, 'tickets' => $tickets, 'showPagination' => is_null(request('all'))]);
    }

    public function buy_ticket_list()
    {
        $gym = Gym::find(session('gym'));

        $buyable_tickets = $gym->buyableTickets; // TODO: filter this to only list quantity > 0 tickets

        return view('user.buy', ['gym' => $gym, 'buyable_tickets' => $buyable_tickets]);
    }

    public function buy_ticket_show($buyable_ticket_id)
    {
        if (session('gym') == null) {
            return redirect()->route('gyms');
        }

        if (Auth::user()->is_receptionist()) {
            return redirect()->route('index');
        }

        $ticket = BuyableTicket::find($buyable_ticket_id);

        $ticket_type = $ticket->isMonthly() ? "Bérlet" : "Jegy";

        return view('user.buy_ticket', ['ticket' => $ticket, 'ticket_type' => $ticket_type]);
    }

    public function buy_ticket_create(Request $request, $buyable_ticket_id)
    {
        $buyable_ticket = BuyableTicket::find($buyable_ticket_id);

        $current_date = new DateTime();
        $expiration = new DateTime();
        $expiration->modify("+1 month");

        Auth::user()->credits -= $buyable_ticket->price;
        Auth::user()->save();

        Ticket::factory()->create([
            'user_id' => Auth::user()->id,
            'gym_id' => $buyable_ticket->gym_id,
            'type_id' => $buyable_ticket->id,
            'bought' => $current_date,
            'expiration' => $expiration,
        ]);

        if ($buyable_ticket->quantity != 999) {
            $buyable_ticket->quantity -= 1;
            $buyable_ticket->save();
        }

        return redirect()->route('index');
    }

    // TODO: get ID
    public function extend_ticket_page(Ticket $ticket)
    {
        if (session('gym') == null) {
            return redirect()->route('gyms');
        }

        if (Auth::user()->is_receptionist()) {
            return redirect()->route('index');
        }

        if (Auth::user()->tickets->where('id', $ticket->id)->count() == 0) {
            return redirect()->route('index');
        }

        if ($ticket->expired()) {
            return redirect()->route('tickets');
        }

        return view('user.extend_ticket', ['ticket' => $ticket]);
    }

    public function extend_ticket(Request $request, Ticket $ticket)
    {
        if (session('gym') == null) {
            return redirect()->route('gyms');
        }

        if (Auth::user()->is_receptionist()) {
            return redirect()->route('index');
        }

        if (Auth::user()->tickets->where('id', $ticket->id)->count() == 0) {
            return redirect()->route('index');
        }

        if ($ticket->usable()) {
            return redirect()->route('tickets');
        }

        if (Auth::user()->credits < $ticket->type->price) {
            return redirect()->route('tickets');
        }
        // TODO: error

        $expiration = Carbon::create();
        $expiration->add(1, 'month');

        $ticket->expiration = $expiration;
        $ticket->save();

        $user = Auth::user();
        $user->credits -= $ticket->type->price;

        return redirect()->route('index');
    }

    public function statistics()
    {
        $gym = Gym::find(session('gym'));

        // People
        $enterances = Enterance::all();
        $people_inside = [11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0];
        foreach ($enterances as $enterance) {
            if ($enterance->gym_id == $gym->id) {
                // TODO: if date is today
                $enter_hour = intval(date_create($enterance->enter)->format('H'));
                $exit_hour = intval(date_create($enterance->enter)->format('H'));

                for ($i = $enter_hour; $i <= $exit_hour; $i++) {
                    if ($i >= 11 && $i <= 16) {
                        $people_inside[$i]++;
                    }

                }
            }
        }

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

                error_log("mins: " . $mins . ", sum: " . $min_sum);

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

        return view('user.statistics', ['gym' => $gym, 'hour_avg' => $hours, 'min_avg' => $minutes, 'people_inside' => $people_inside]);
    }
}
