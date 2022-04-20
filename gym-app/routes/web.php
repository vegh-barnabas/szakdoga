<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Gym;
use App\Models\Enterance;

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

Route::get('/home', function () {
    if(session('gym') == null) return redirect()->route('gyms');

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
            $hour = intval(date_create($enterance->enter)->format('H'));
            if($hour >= 11 && $hour <= 16) $people_inside[$hour]++;
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
