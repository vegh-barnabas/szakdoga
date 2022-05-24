<?php

namespace App\Http\Controllers;

use App\Models\BuyableTicket;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class BuyableTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->is_receptionist()) {
            $gym = Gym::all()->where('id', Auth::user()->prefered_gym)->first();
            $monthly_tickets = BuyableTicket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
                return $ticket->isMonthly();
            })->values();

            $tickets = BuyableTicket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
                return !$ticket->isMonthly();
            })->values();

            $all_tickets = $monthly_tickets->merge($tickets);

            return view('receptionist.buyable-tickets', ['tickets' => $all_tickets, 'gym_name' => $gym->name]);
        } else if (Auth::user()->is_admin()) {
            $gym_name = Gym::all()->pluck('name')->implode(', ');

            $monthly_tickets = BuyableTicket::all()->filter(function ($ticket) {
                return $ticket->isMonthly();
            })->values();

            $tickets = BuyableTicket::all()->filter(function ($ticket) {
                return !$ticket->isMonthly();
            })->values();

            $all_tickets = $monthly_tickets->merge($tickets);

            return view('admin.buyable-tickets.index', ['tickets' => $all_tickets, 'gym_name' => $gym_name]);
        } else {
            abort(403);
        }
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

        $gyms = Gym::all();

        return view('admin.buyable-tickets.create', ['gyms' => $gyms]);
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
                'gym_id' => 'required|in:' . Gym::all()->pluck('id')->implode(','),
                'name' => [
                    'required',
                    'min:3',
                    'max:32',
                    Rule::unique('buyable_tickets')->where(function ($query) use ($request) {
                        return $query->where('gym_id', $request->gym_id);
                    }),
                ],
                'type' => 'required|in:monthly,one-time',
                'description' => 'min:6|max:128',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|integer|min:0',
            ],
        );

        $validated['hidden'] = 0;

        $ticket = BuyableTicket::create($validated);

        return redirect()->route('buyable-tickets.index')->with('create', $ticket->name);
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

        $ticket = BuyableTicket::all()->where('id', $id)->first();

        if ($ticket == null) {
            abort(403);
        }

        $gyms = Gym::all();

        return view('admin.buyable-tickets.edit', ['ticket' => $ticket, 'gyms' => $gyms]);
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

        $ticket = BuyableTicket::all()->where('id', $id)->first();

        if ($ticket == null) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'gym_id' => 'in:' . Gym::all()->pluck('id')->implode(','),
                'name' => [
                    'min:3',
                    'max:32',
                    Rule::unique('buyable_tickets')->where(function ($query) use ($request) {
                        return $query->where('gym_id', $request->gym_id);
                    })->ignore($ticket->id),
                ],
                'type' => 'in:monthly,one-time',
                'description' => 'min:6|max:128',
                'quantity' => 'integer|min:1',
                'price' => 'integer|min:0',
                'hidden' => 'boolean',
            ],
        );

        $ticket->update($validated);

        return redirect()->route('buyable-tickets.index')->with('edit', $ticket->name);
    }

    public function hide_form($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $ticket = BuyableTicket::all()->where('id', $id)->first();

        if ($ticket == null) {
            abort(403);
        }

        $gyms = Gym::all();

        return view('admin.buyable-tickets.hide', ['ticket' => $ticket, 'gyms' => $gyms]);
    }

    public function hide($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $ticket = BuyableTicket::all()->where('id', $id)->first();

        if ($ticket == null) {
            abort(403);
        }

        $ticket->hidden = !$ticket->hidden;
        $ticket->save();

        return redirect()->route('buyable-tickets.index')->with('hide', $ticket->name);
    }
}
