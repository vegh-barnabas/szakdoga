<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    public function edit_ticket($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $ticket = Ticket::all()->where('id', $id)->first();

        if ($ticket == null) {
            abort(403);
        }

        if ($ticket->isMonthly()) {
            return Redirect::back();
        }

        return view('admin.tickets.edit-ticket', ['ticket' => $ticket]);

    }

    public function edit_monthly($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $ticket = Ticket::all()->where('id', $id)->first();

        if ($ticket == null) {
            abort(403);
        }

        if (!$ticket->isMonthly()) {
            return Redirect::back();
        }

        return view('admin.tickets.edit-monthly-ticket', ['ticket' => $ticket]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_ticket(Request $request, $id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'expiration' => 'date_format:Y-m-d',
            ],
        );

        $ticket = Ticket::all()->where('id', $id)->first();

        if ($ticket->isMonthly()) {
            return abort(403);
        }

        $ticket->update($validated);

        return redirect()->route('purchased-tickets')->with('edit', $ticket->name);
    }

    public function update_monthly(Request $request, $id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'bought' => 'date_format:Y-m-d',
                'expiration' => 'date_format:Y-m-d',
            ],
        );

        $ticket = Ticket::all()->where('id', $id)->first();

        if (!$ticket->isMonthly()) {
            return abort(403);
        }

        $ticket->update($validated);

        return redirect()->route('purchased-monthly')->with('edit', $ticket->name);
    }

    public function delete($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $ticket = Ticket::all()->where('id', $id)->first();

        return view('admin.tickets.delete', ['ticket' => $ticket]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $ticket = Ticket::all()->where('id', $id)->first();

        $ticket_name = $ticket->type->name;
        $ticket_is_monthly = $ticket->isMonthly();

        if ($ticket == null) {
            abort(403);
        }

        $deleted = $ticket->delete();
        if (!$deleted) {
            return abort(500);
        }

        if ($ticket_is_monthly) {
            return redirect()->route('purchased-monthly')->with('delete', $ticket_name);
        } else {
            return redirect()->route('purchased-tickets')->with('delete', $ticket_name);
        }
    }
}
