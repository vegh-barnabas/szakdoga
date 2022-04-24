<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Gym;
use App\Models\Enterance;
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

    }
})->name('let-in-2')->middleware('auth');

Route::get('/let-out', function (Request $request) {
    if(Auth::user()->is_receptionist) {
        return view('receptionist.let-out');
    }
})->name('let-out')->middleware('auth');

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

        $tickets = Auth::user()->tickets->where('gym_id', $gym->id)->sortByDesc('expiration');

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
})->name('buyticket')->middleware('auth');

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

            $count++;
        }
    }

    $min_avg = $min_sum / $count;

    $hours = intdiv($min_avg, 60);
    $minutes = $min_avg % 60;

    return view('user.statistics', ['gym' => $gym, 'hour_avg' => $hours, 'min_avg' => $minutes, 'people_inside' => $people_inside]);
})->name('userstats')->middleware('auth');
