<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Gym;
use App\Models\Enterance;
use App\Models\BuyableTicket;
use App\Models\Ticket;
use App\Models\Locker;
use App\Models\User;

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

Route::get('/', function () {
    return view('gyms.index', ['gyms' => Gym::all()]);
})->name('gyms')->middleware('auth');

Route::post('/', function (Request $request) {
    if(!is_numeric($request['gymId'])) return redirect()->route('gyms');

    if(Gym::all()->pluck('id')->contains($request['gymId'])) {
        session(['gym' => $request['gymId']]);
        return redirect()->route('index');
    }
    else return redirect()->route('gyms')->middleware('auth');
});

Route::get('/let-in', function (Request $request) {
    if(Auth::user()->is_receptionist) {
        return view('receptionist.let-in');
    }
})->name('let-in')->middleware('auth');

Route::post('/let-in', function (Request $request) {
    if(Auth::user()->is_receptionist) {

        // $validated = $request->validate(
        //     // Validation rules
        //     [
        //         // 'enterance_code' => 'required',
        //         // '' => 'required|accepted'
        //     ],
        // );

        // error_log($request->input('enterance_code'));

        $code = $request->input('enterance_code');

        return redirect()->route('let-in-2', $code);
    }
})->name('let-in')->middleware('auth');

Route::get('/let-in/{code}', function (Request $request, $code) {
    if(Auth::user()->is_receptionist) {
        $ticket = Ticket::all()->where('code', $code)->first();

        if($ticket === null) return view('receptionist.let-in'); // TODO: error message
        if($ticket->exit !== null) return view('receptionist.let-in'); // TODO: error message

        $user = $ticket->user;

        $lockers = Locker::all()->where('gender', $user->gender)->where('user_id', null);

        return view('receptionist.let-in-2', ['user' => $user, 'ticket' => $ticket, 'lockers' => $lockers, 'code' => $code]);
    }
})->name('let-in-2')->middleware('auth');

Route::post('/let-in/{code}', function (Request $request, $code) {
    if(Auth::user()->is_receptionist) {

        // error_log($request->input('locker'));
        // error_log($request->input('keyGiven'));

        $ticket = Ticket::all()->where('code', $code)->first();
        $user = $ticket->user;
        $lockers = Locker::all()->where('gender', $user->gender)->where('user_id', null);

        $validated = $request->validate(
            // Validation rules
            [
                'locker' => 'required', // TODO: check if locker is in lockers
                'keyGiven' => 'required|accepted',
            ],
        );

        $locker = Locker::all()->where('number', $validated['locker']);

        $user->locker = $locker;

        // TODO: create enterance with ticket
        $enter_time = new DateTime();

        $new_enterance = Enterance::factory()->create([
            'gym_id' => $ticket->gym_id,
            'user_id' => $user->id,
            'ticket_id' => $ticket->id,
            'enter' => $enter_time,
            'exit' => null
        ]);

        return redirect()->route('let-in');
    }
})->name('let-in-2')->middleware('auth');

Route::get('/let-out/{code}', function (Request $request, $code) {
    if(Auth::user()->is_receptionist) {
        $user = User::all()->where('exit_code', $code)->first();
        if($user === null) return view('receptionist.let-out'); // TODO: error message

        $enterace = $user->enterances()->sortByDesc('enter')->first();
        if($enterace->exit != null) return view('receptionist.let-out'); // TODO: error message

        $exit_time = new DateTime();
        $enterance->exit = $exit_time;

        return redirect->route('let-out-2');
    }
})->name('let-out-2')->middleware('auth');

Route::post('/let-out/{code}', function (Request $request, $code) {
    if(Auth::user()->is_receptionist) {
        return view('receptionist.let-out');
    }
})->name('let-out-2')->middleware('auth');

Route::get('/let-out/{code}', function (Request $request, $code) {
    if(Auth::user()->is_receptionist) {
        return view('receptionist.let-out-2');
    }
})->name('let-out-2')->middleware('auth');

Route::get('/settings', function (Request $request) {
    if(Auth::user()->is_receptionist) {
        return view('receptionist.let-in');
    }
})->name('settings')->middleware('auth');

Route::get('/home', function () {
    if(session('gym') == null) return redirect()->route('gyms');

    if(Auth::user()->is_receptionist) {
        $gym = Gym::find(session('gym'));
        $enterances = Enterance::all()->where('gym_id', $gym->id)->where('exit', null);

        $tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->type->type == 'jegy';
        })->take(5);
        // TODO: rename this
        $berlets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->type->type == 'bérlet';
        })->sortByDesc('bought')->take(5);

        return view('receptionist.index', ['enterances' => $enterances, 'tickets' => $tickets, 'berlets' => $berlets]);
    }
    else {
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

Route::get('/buy', function () {
    $gym = Gym::find(session('gym'));

    return view('user.buy', ['gym' => $gym]);
})->name('buyticketpage')->middleware('auth');

Route::get('/tickets', function () {
    if(session('gym') == null) return redirect()->route('gyms');

    if(Auth::user()->is_receptionist) return redirect()->route('home');

    $gym = Gym::find(session('gym'));

    $tickets = Auth::user()->tickets()
        ->paginate(8)
        ->where('gym_id', $gym->id)
        ->sortByDesc('expiration')
        ->sortBy(function ($ticket) {
            return $ticket->type->type;
        });

    return view('user.tickets', ['gym' => $gym, 'tickets' => $tickets, 'showPagination' => is_null(request('all'))]);

})->name('tickets')->middleware('auth');


Route::get('/buy/{ticket}', function (Request $request, $buyable_ticket_id) {
    if(session('gym') == null) return redirect()->route('gyms');

    if(Auth::user()->is_receptionist) return redirect()->route('index');

    $ticket = BuyableTicket::find($buyable_ticket_id);

    $ticket_type = $ticket->type == "bérlet" ? "Bérlet" : "Jegy";

    return view('user.buy_ticket', ['ticket' => $ticket, 'ticket_type' => $ticket_type]);
})->name('buy_ticket')->middleware('auth');

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
        'expiration' => $expiration
    ]);

    return redirect()->route('index');
})->name('buy_ticket')->middleware('auth');


Route::get('/extend/{ticket}', function (Request $request, Ticket $ticket) {
    if(session('gym') == null) return redirect()->route('gyms');

    if(Auth::user()->is_receptionist) return redirect()->route('index');

    if(Auth::user()->tickets->where('id', $ticket->id)->count() == 0) return redirect()->route('index');

    if($ticket->expiration >= date('Y-m-d H:i:s')) return redirect()->route('tickets');

    return view('user.extend_ticket', ['ticket' => $ticket]);
})->name('extend_ticket')->middleware('auth');

Route::post('/extend/{ticket}', function (Request $request, Ticket $ticket) {
    if(session('gym') == null) return redirect()->route('gyms');

    if(Auth::user()->is_receptionist) return redirect()->route('index');

    if(Auth::user()->tickets->where('id', $ticket->id)->count() == 0) return redirect()->route('index');

    if(strtotime($ticket->expiration) >= date('Y-m-d H:i:s')) return redirect()->route('tickets');

    if(Auth::user()->credits < $ticket->type->price) return redirect()->route('tickets'); // TODO: error

    $expiration = new DateTime();
    $expiration->modify("+1 month");
    $ticket->expiration = $expiration;
    $ticket->save();

    Auth::user()->credits -= $ticket->type->price;
    Auth::user()->save();

    return redirect()->route('index');
})->name('extend_ticket')->middleware('auth');

Route::get('/stats', function () {
    $gym = Gym::find(session('gym'));

    // People
    $enterances = Enterance::all();
    $people_inside = [11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0];
    foreach($enterances as $enterance) {
        if($enterance->gym_id == $gym->id) {
            // TODO: if date is today
            $enter_hour = intval(date_create($enterance->enter)->format('H'));
            $exit_hour = intval(date_create($enterance->enter)->format('H'));

            for ($i = $enter_hour; $i <= $exit_hour; $i++) {
                if($i >= 11 && $i <= 16) $people_inside[$i]++;
            }
        }
    }

    // Enterance avg
    $min_sum = 0;
    $count = 0;
    foreach(Auth::user()->enterances as $enterance) {
        if($enterance->gym_id == $gym->id) {
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
