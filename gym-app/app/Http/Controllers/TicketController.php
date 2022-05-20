<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function index_ticket()
    {
        //
    }

    public function index_monthly()
    {
        //
    }

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

        return view('admin.edit-purchased-ticket', ['ticket' => $ticket]);

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

        return view('admin.edit-purchased-ticket', ['ticket' => $ticket]);

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
                'expiration' => 'required|date',
            ],
        );

        $ticket = Ticket::all()->where('id', $id)->first();

        if ($ticket->isMonthly()) {
            return abort(403);
        }

        $ticket->update($validated);

        return Redirect::back()->with('success', $ticket->name);
    }

    public function update_monthly(Request $request, $id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $validated = $request->validate(
            [
                'bought' => 'required|date',
                'expiration' => 'required|date',
            ],
        );

        $ticket = Ticket::all()->where('id', $id)->first();

        if (!$ticket->isMonthly()) {
            return abort(403);
        }

        $ticket->update($validated);

        return Redirect::back()->with('success', $ticket->name);
    }

    public function delete($id)
    {
        if (!Auth::user()->is_admin()) {
            abort(403);
        }

        $ticket = Ticket::all()->where('id', $id)->first();

        return view('admin.delete-purchased-ticket', ['ticket' => $ticket]);
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

        if ($ticket == null) {
            abort(403);
        }

        $deleted = $ticket->delete();
        if (!$deleted) {
            return abort(500);
        }

        return Redirect::to('purchased-tickets')->with('deleted', $ticket_name);
    }
}
