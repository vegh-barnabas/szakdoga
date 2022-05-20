<?php

use App\Http\Controllers\BuyableTicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

/* Admin routes */
Route::get('/categories/{id}/delete', [CategoryController::class, 'delete'])->name('categories.delete')->middleware('auth');
Route::resource('categories', CategoryController::class)->middleware('auth');

Route::get('/buyable-tickets/{id}/hide', [BuyableTicketController::class, 'hide_form'])->name('buyable-tickets.hide')->middleware('auth');
Route::patch('/buyable-tickets/{id}/hide', [BuyableTicketController::class, 'hide'])->name('buyable-tickets.hide')->middleware('auth');
Route::resource('buyable-tickets', BuyableTicketController::class)->middleware('auth');

Route::get('/gyms/{id}/delete', [GymController::class, 'delete'])->name('gyms.delete')->middleware('auth');
Route::resource('gyms', GymController::class)->middleware('auth');

Route::resource('tickets', TicketController::class)->middleware('auth');
Route::get('tickets/edit-ticket/{id}', [TicketController::class, 'edit_ticket'])->name('tickets.edit')->middleware('auth');
Route::patch('tickets/edit-ticket/{id}', [TicketController::class, 'update_ticket'])->name('tickets.edit')->middleware('auth');
Route::get('tickets/edit-monthly/{id}', [TicketController::class, 'edit_monthly'])->name('monthly-tickets.edit')->middleware('auth');
Route::patch('tickets/edit-monthly/{id}', [TicketController::class, 'update_monthly'])->name('monthly-tickets.edit')->middleware('auth');

Route::resource('users', UserController::class)->middleware('auth');

/* Guest routes */
Route::get('/', [GuestController::class, 'choose_gym_page'])->name('guest.gyms.list')->middleware('auth');
Route::post('/', [GuestController::class, 'choose_gym'])->name('guest.gyms.choose')->middleware('auth');
Route::get('/tickets', [GuestController::class, 'tickets'])->name('guest.tickets')->middleware('auth');

Route::get('/buy-ticket', [GuestController::class, 'buy_ticket_list'])->name('guest.buy-ticket')->middleware('auth');
Route::get('/buy-ticket/{id}', [GuestController::class, 'buy_ticket_show'])->name('guest.buy-ticket.show')->middleware('auth');
Route::post('/buy-ticket/{id}', [GuestController::class, 'buy_ticket_create'])->name('guest.buy-ticket.create')->middleware('auth');

Route::get('/extend-ticket/{id}', [GuestController::class, 'extend_ticket_page'])->name('guest.extend-ticket.show')->middleware('auth');
Route::patch('/extend-ticket/{id}', [GuestController::class, 'extend_ticket'])->name('guest.extend-ticket.extend')->middleware('auth');

Route::get('/statistics', [GuestController::class, 'statistics'])->name('guest.statistics')->middleware('auth');

/* Receptionist routes */
Route::get('/let-in', [ReceptionistController::class, 'let_in_index_page'])->name('receptionist.let-in.index-page')->middleware('auth');
Route::post('/let-in', [ReceptionistController::class, 'let_in_index'])->name('receptionist.let-in.index')->middleware('auth');
Route::get('/let-in/{id}', [ReceptionistController::class, 'let_in_page'])->name('receptionist.let-in.page')->middleware('auth');
Route::post('/let-in/{id}', [ReceptionistController::class, 'let_in'])->name('receptionist.let-in')->middleware('auth');

Route::get('/let-out', [ReceptionistController::class, 'let_out_index_page'])->name('receptionist.let-out.index-page')->middleware('auth');
Route::post('/let-out', [ReceptionistController::class, 'let_out_index'])->name('receptionist.let-out.index')->middleware('auth');
Route::get('/let-out/{id}', [ReceptionistController::class, 'lout_out_page'])->name('receptionist.let-out.page')->middleware('auth');
Route::post('/let-out/{id}', [ReceptionistController::class, 'let_out'])->name('receptionist.let-out')->middleware('auth');

/* Mutual Routes */
// Home - User & Receptionist
Route::get('/home', function () {
    if (session('gym') == null && !Auth::user()->is_admin()) {
        return redirect()->route('gyms');
    }

    if (Auth::user()->is_receptionist()) {
        $gym = Gym::find(session('gym'));
        $enterances = Enterance::all()->where('gym_id', $gym->id)->where('exit', null);

        $tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->take(5);
        $monthly_tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->sortByDesc('bought')->take(5);

        return view('receptionist.index', ['enterances' => $enterances, 'tickets' => $tickets, 'monthly_tickets' => $monthly_tickets, 'gym' => $gym]);
    } else if (Auth::user()->is_admin()) {

        $gym_name = Gym::all()->pluck('name')->implode(',');

        $tickets = Ticket::all()->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->sortByDesc('bought')->take(5);
        $monthly_tickets = Ticket::all()->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->sortByDesc('bought')->take(5);

        $active_enterances = Enterance::all()->filter(function ($enterance) {
            return !$enterance->exited();
        });

        // TODO: receptionist login
        $active_receptionists = User::all()->where('is_receptionist()');

        return view('admin.index', ['gym_name' => $gym_name, 'tickets' => $tickets, 'monthly_tickets' => $monthly_tickets, 'active_enterances' => $active_enterances, 'active_receptionists' => $active_receptionists]);
    } else {
        $gym = Gym::find(session('gym'));

        $tickets = Auth::user()->tickets
            ->where('gym_id', $gym->id)
            ->filter(function ($ticket) {
                return !$ticket->isMonthly() && $ticket->useable();
            })
            ->sortByDesc('expiration');

        $monthly_tickets = Auth::user()->tickets
            ->where('gym_id', $gym->id)
            ->filter(function ($ticket) {
                return $ticket->isMonthly() && $ticket->useable();
            })
            ->sortByDesc('expiration');

        $last_enterance = Auth::user()->enterances->where('gym_id', $gym->id)->sortByDesc('enter')->first();

        if ($last_enterance == null) {
            $last_enterance_data = null;
        } else {
            // Duration
            $start = new DateTime($last_enterance->enter);
            $end = new DateTime($last_enterance->exit);
            $mins = ($end->getTimestamp() - $start->getTimestamp()) / 60;
            $hours = intdiv($mins, 60);
            $minutes = $mins % 60;

            $last_enterance_data = [
                'last_enterance' => $last_enterance,
                'dur_hours' => $hours,
                'dur_minutes' => $minutes,
            ];
        }

        return view('user.index', ['gym' => $gym, 'tickets' => $tickets, 'monthly_tickets' => $monthly_tickets, 'last_enterance_data' => $last_enterance_data]);
    }
})->name('index')->middleware('auth');

// Settings - User & Receptionist
// TODO: put these in different controllers and redirect
Route::get('/settings', function () {
    if (Auth::user()->is_receptionist()) {
        return view('receptionist.settings');
    } else if (Auth::user()->is_admin()) {
        abort(403);
    } else {
        $gyms = Gym::all();
        $selected_gym_id = Auth::user()->prefered_gym;
        $current_gym = session('gym');

        return view('user.settings', ['gyms' => $gyms, 'selected_gym_id' => $selected_gym_id, 'current_gym' => $current_gym]);
    }

})->name('settings')->middleware('auth');

// Purchased monthly tickets - Receptionist & Admin
Route::get('/purchased-monthly', function () {
    if (Auth::user()->is_receptionist()) {
        $gym = Gym::find(session('gym'));
        $purchased_tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->values()->sortByDesc('expiration');

        return view('receptionist.purchased-monthly', ['tickets' => $purchased_tickets, 'gym_name' => $gym->name]);
    } else if (Auth::user()->is_admin()) {
        $gym_name = Gym::all()->pluck('name')->implode(',');

        $purchased_tickets = Ticket::all()->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->values()->sortByDesc('expiration');

        return view('admin.purchased-monthly', ['tickets' => $purchased_tickets, 'gym_name' => $gym_name]);
    } else {
        abort(403);
    }
})->name('purchased-monthly')->middleware('auth');

// Purchased tickets - Receptionist & Admin
Route::get('/purchased-tickets', function () {
    if (Auth::user()->is_receptionist()) {
        $gym = Gym::find(session('gym'));
        $purchased_tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->values()->sortByDesc('expiration');

        return view('receptionist.purchased-tickets', ['tickets' => $purchased_tickets, 'gym_name' => $gym->name]);
    } else if (Auth::user()->is_admin()) {
        $gym_name = Gym::all()->pluck('name')->implode(',');

        $purchased_tickets = Ticket::all()->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->values()->sortByDesc('expiration');

        return view('admin.purchased-tickets', ['tickets' => $purchased_tickets, 'gym_name' => $gym_name]);
    } else {
        abort(403);
    }
})->name('purchased-tickets')->middleware('auth');
