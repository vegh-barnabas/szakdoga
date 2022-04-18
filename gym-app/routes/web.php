<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/home', function () {
    return view('user.index');
})->name('index')->middleware('auth');

Route::get('/buy', function () {
    return view('user.buy');
})->name('buyticket')->middleware('auth');

Route::get('/stats', function () {
    return view('user.statistics');
})->name('userstats')->middleware('auth');
