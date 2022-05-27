<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;

class AddToSessionAfterLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (Auth::user()->permission == 'receptionist') {
            session(['gym' => Auth::user()->prefered_gym]);
        } else if (Auth::user()->permission == 'user' && Auth::user()->prefered_gym != null) {
            session(['gym' => Auth::user()->prefered_gym]);
        }
    }
}
