<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Gym;

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
    else return redirect()->route('gyms');
});

Route::get('/home', function () {
    $gym = Gym::find(session('gym'));

    return view('user.index', ['gym' => $gym]);
})->name('index')->middleware('auth');

Route::get('/buy', function () {
    return view('user.buy');
})->name('buyticket')->middleware('auth');

Route::get('/stats', function () {
    return view('user.statistics');
})->name('userstats')->middleware('auth');
