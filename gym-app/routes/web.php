<?php

use App\Models\BuyableTicket;
use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Locker;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();

// TODO: make error texts, check input validation

/* --- Mutual Routes --- */

// Home - User & Receptionist
Route::get('/home', function () {
    if (session('gym') == null) {
        return redirect()->route('gyms');
    }

    if (Auth::user()->is_receptionist) {
        $gym = Gym::find(session('gym'));
        $enterances = Enterance::all()->where('gym_id', $gym->id)->where('exit', null);

        $tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->type->type == 'jegy';
        })->take(5);
        // TODO: rename this
        $berlets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->type->type == 'bérlet';
        })->sortByDesc('bought')->take(5);

        return view('receptionist.index', ['enterances' => $enterances, 'tickets' => $tickets, 'berlets' => $berlets, 'gym' => $gym]);
    } else {
        $gym = Gym::find(session('gym'));

        $tickets = Auth::user()->tickets
            ->where('gym_id', $gym->id)
            ->filter(function ($ticket) {
                return $ticket->useable();
            })
            ->sortByDesc('expiration');

        $last_enterance = Auth::user()->enterances->where('gym_id', $gym->id)->sortByDesc('enter')->first();

        // Duration
        $start = new DateTime($last_enterance->enter);
        $end = new DateTime($last_enterance->exit);
        $mins = ($end->getTimestamp() - $start->getTimestamp()) / 60;
        $hours = intdiv($mins, 60);
        $minutes = $mins % 60;

        return view('user.index', ['gym' => $gym, 'tickets' => $tickets, 'last_enterance' => $last_enterance, 'dur_hours' => $hours, 'dur_minutes' => $minutes]);
    }
})->name('index')->middleware('auth');

// Settings - User & Receptionist
Route::get('/settings', function (Request $request) {
    if (Auth::user()->is_receptionist) {
        return view('receptionist.settings');
    } else {
        return view('user.settings');
    }

})->name('settings')->middleware('auth');

/* --- User Routes --- */

// Choose Gym
Route::get('/', function () {
    if (session('gym') != null) {
        return redirect()->route('index');
    }

    if (Auth::user()->prefered_gym != null) {
        session(['gym' => Auth::user()->prefered_gym]);
        return redirect()->route('index');
    }

    return view('gyms.index', ['gyms' => Gym::all()]);
})->name('gyms')->middleware('auth');

// Gym Index
Route::post('/', function (Request $request) {
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
});

// Buy Ticket
Route::get('/buy', function () {
    $gym = Gym::find(session('gym'));

    return view('user.buy', ['gym' => $gym]);
})->name('buyticketpage')->middleware('auth'); // TODO: rename this to buy_ticket

// Check Tickets
Route::get('/tickets', function () {
    if (session('gym') == null) {
        return redirect()->route('gyms');
    }

    if (Auth::user()->is_receptionist) {
        return redirect()->route('home');
    }

    $gym = Gym::find(session('gym'));

    // TODO: paginaion
    $tickets = Auth::user()->tickets
        ->where('gym_id', $gym->id)
        ->sortByDesc('expiration')
        ->sortBy(function ($ticket) {
            return $ticket->type->type;
        });

    return view('user.tickets', ['gym' => $gym, 'tickets' => $tickets, 'showPagination' => is_null(request('all'))]);

})->name('tickets')->middleware('auth');

// Show Staticstics
Route::get('/stats', function () {
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

    $min_avg = $min_sum / $count;

    $hours = intdiv($min_avg, 60);
    $minutes = $min_avg % 60;

    return view('user.statistics', ['gym' => $gym, 'hour_avg' => $hours, 'min_avg' => $minutes, 'people_inside' => $people_inside]);
})->name('userstats')->middleware('auth');

// Extend Ticket
Route::get('/extend/{ticket}', function (Request $request, Ticket $ticket) {
    if (session('gym') == null) {
        return redirect()->route('gyms');
    }

    if (Auth::user()->is_receptionist) {
        return redirect()->route('index');
    }

    if (Auth::user()->tickets->where('id', $ticket->id)->count() == 0) {
        return redirect()->route('index');
    }

    if ($ticket->expiration >= date('Y-m-d H:i:s')) {
        return redirect()->route('tickets');
    }

    return view('user.extend_ticket', ['ticket' => $ticket]);
})->name('extend_ticket')->middleware('auth');

// Extend Ticket POST
Route::post('/extend/{ticket}', function (Request $request, Ticket $ticket) {
    if (session('gym') == null) {
        return redirect()->route('gyms');
    }

    if (Auth::user()->is_receptionist) {
        return redirect()->route('index');
    }

    if (Auth::user()->tickets->where('id', $ticket->id)->count() == 0) {
        return redirect()->route('index');
    }

    if (strtotime($ticket->expiration) >= date('Y-m-d H:i:s')) {
        return redirect()->route('tickets');
    }

    if (Auth::user()->credits < $ticket->type->price) {
        return redirect()->route('tickets');
    }
    // TODO: error

    $expiration = new DateTime();
    $expiration->modify("+1 month");
    $ticket->expiration = $expiration;
    $ticket->save();

    $user = Auth::user();
    $user->credits -= $ticket->type->price;

    return redirect()->route('index');
})->name('extend_ticket')->middleware('auth');

// Buy Ticket
Route::get('/buy/{ticket}', function (Request $request, $buyable_ticket_id) {
    if (session('gym') == null) {
        return redirect()->route('gyms');
    }

    if (Auth::user()->is_receptionist) {
        return redirect()->route('index');
    }

    $ticket = BuyableTicket::find($buyable_ticket_id);

    $ticket_type = $ticket->type == "bérlet" ? "Bérlet" : "Jegy";

    return view('user.buy_ticket', ['ticket' => $ticket, 'ticket_type' => $ticket_type]);
})->name('buy_ticket')->middleware('auth');

// Buy Ticket POST
Route::post('/buy/{ticket}', function (Request $request, $buyable_ticket_id) {
    $buyable_ticket = BuyableTicket::find($buyable_ticket_id);

    $current_date = new DateTime();
    $expiration = new DateTime();
    $expiration->modify("+1 month");

    $new_ticket = Ticket::factory()->create([
        'user_id' => Auth::user()->id,
        'gym_id' => $buyable_ticket->gym_id,
        'type_id' => $buyable_ticket->id,
        'bought' => $current_date,
        'expiration' => $expiration,
    ]);

    return redirect()->route('index');
})->name('buy_ticket')->middleware('auth');

/* --- Receptionist Routes --- */

// Let-in index page
Route::get('/let-in', function () {
    if (!Auth::user()->is_receptionist) {
        abort(403);
    }

    return view('receptionist.let-in');
})->name('let-in')->middleware('auth');

// Let-in index page POST
Route::post('/let-in', function (Request $request) {
    if (!Auth::user()->is_receptionist) {
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
        return Redirect::back()->with('doesnt_exist', ['code' => $code]);
    }

    $user = $ticket->user;
    if ($user->enterances->where('exit', null)->count() > 0) {
        return Redirect::back()->with('error', ['code' => $code, 'user' => $user->name]);
    }

    return redirect()->route('let-in-2', $code);
})->name('let-in')->middleware('auth');

// List data of user page
Route::get('/let-in/{code}', function ($code) {
    if (!Auth::user()->is_receptionist) {
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

    $lockers = Locker::all()->where('gender', $user->gender)->where('user_id', null);

    return view('receptionist.let-in-2', ['user' => $user, 'ticket' => $ticket, 'lockers' => $lockers, 'code' => $code]);
})->name('let-in-2')->middleware('auth');

// Let someone in POST
Route::post('/let-in/{code}', function (Request $request, $code) {
    if (!Auth::user()->is_receptionist) {
        abort(403);
    }

    $ticket = Ticket::all()->where('code', $code)->first();
    $user = $ticket->user;

    $validated = $request->validate(
        // Validation rules
        [
            'locker' => [
                'required',
                Rule::in(Locker::all()->pluck('number')),
            ],
            'keyGiven' => [
                'required',
                'accepted',
            ],
        ],
    );

    $locker = Locker::all()->where('number', $validated['locker']);

    $user->locker = $locker;

    $enter_time = new DateTime();
    $new_enterance = Enterance::factory()->create([
        'gym_id' => $ticket->gym_id,
        'user_id' => $user->id,
        'ticket_id' => $ticket->id,
        'enter' => $enter_time,
        'exit' => null,
    ]);

    return Redirect::to('let-in')->with('success', $user->name);
})->name('let-in-2')->middleware('auth');

// Let-out index page
Route::get('/let-out', function () {
    if (!Auth::user()->is_receptionist) {
        abort(403);
    }

    return view('receptionist.let-out');
})->name('let-out')->middleware('auth');

// Let-out index page POST
Route::post('/let-out', function (Request $request) {
    if (!Auth::user()->is_receptionist) {
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
        return Redirect::back()->with('error-not-found', $exit_code);
    }
    if ($user->enterances->where('exit', null)->count() == 0) {
        return Redirect::back()->with('error', ['code' => $user->exit_code, 'user' => $user->name]);
    } // TODO: rename this exit code

    return redirect()->route('let-out-2', $user->exit_code);
})->name('let-out')->middleware('auth');

// List data of user page
Route::get('/let-out/{code}', function ($code) {
    if (!Auth::user()->is_receptionist) {
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

    return view('receptionist.let-out-2', ['user' => $user, 'enterance' => $enterance]);
})->name('let-out-2')->middleware('auth');

// Let someone out POST
Route::post('/let-out/{code}', function (Request $request, $code) {
    if (!Auth::user()->is_receptionist) {
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

    // TODO: fix this
    $user->locker->user_id = null;
    $user->locker->save();
    $user->locker_id = null;
    $user->save();

    return Redirect::to('let-out')->with('success', $user->name);
})->name('let-out-2')->middleware('auth');

/* --- Admin Routes --- */
