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

Route::get('/buyable-tickets/{id}/hide', [BuyableTicketController::class, 'hide_form'])->name('buyable-tickets.hide.index')->middleware('auth');
Route::patch('/buyable-tickets/{id}/hide', [BuyableTicketController::class, 'hide'])->name('buyable-tickets.hide')->middleware('auth');
Route::resource('buyable-tickets', BuyableTicketController::class)->middleware('auth');

Route::get('/gyms/{id}/delete', [GymController::class, 'delete'])->name('gyms.delete')->middleware('auth');
Route::resource('gyms', GymController::class)->middleware('auth');

Route::get('/tickets/{id}/delete', [TicketController::class, 'delete'])->name('ticket.delete')->middleware('auth');
// Route::delete('/tickets/{id}/delete', [TicketController::class, 'destroy'])->name('ticket.destroy')->middleware('auth');
Route::get('/tickets/edit-ticket/{id}', [TicketController::class, 'edit_ticket'])->name('ticket.edit.index')->middleware('auth');
Route::patch('/tickets/edit-ticket/{id}', [TicketController::class, 'update_ticket'])->name('ticket.edit')->middleware('auth');
Route::get('/tickets/edit-monthly/{id}', [TicketController::class, 'edit_monthly'])->name('monthly-ticket.edit.index')->middleware('auth');
Route::patch('/tickets/edit-monthly/{id}', [TicketController::class, 'update_monthly'])->name('monthly-ticket.edit')->middleware('auth');
Route::resource('tickets', TicketController::class)->middleware('auth');

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

Route::patch('/settings', [GuestController::class, 'settings'])->name('guest.settings')->middleware('auth');
Route::patch('/settings/sensitive', [GuestController::class, 'sensitive_settings'])->name('guest.sensitive-settings')->middleware('auth');

/* Receptionist routes */
Route::get('/entered-users', [ReceptionistController::class, 'entered_users'])->name('receptionist.entered-users')->middleware('auth');

Route::get('/let-in', [ReceptionistController::class, 'let_in_index_page'])->name('receptionist.let-in.index-page')->middleware('auth');
Route::post('/let-in', [ReceptionistController::class, 'let_in_index'])->name('receptionist.let-in.index')->middleware('auth');
Route::get('/let-in/{id}', [ReceptionistController::class, 'let_in_page'])->name('receptionist.let-in.page')->middleware('auth');
Route::post('/let-in/{id}', [ReceptionistController::class, 'let_in'])->name('receptionist.let-in')->middleware('auth');

Route::get('/let-out', [ReceptionistController::class, 'let_out_index_page'])->name('receptionist.let-out.index-page')->middleware('auth');
Route::post('/let-out', [ReceptionistController::class, 'let_out_index'])->name('receptionist.let-out.index')->middleware('auth');
Route::get('/let-out/{id}', [ReceptionistController::class, 'let_out_page'])->name('receptionist.let-out.page')->middleware('auth');
Route::post('/let-out/{id}', [ReceptionistController::class, 'let_out'])->name('receptionist.let-out')->middleware('auth');

Route::get('/add-credits', [ReceptionistController::class, 'add_credits_index'])->name('receptionist.add-credits.index')->middleware('auth');
Route::post('/add-credits', [ReceptionistController::class, 'add_credits'])->name('receptionist.add-credits')->middleware('auth');

Route::patch('/settings/sensitive', [ReceptionistController::class, 'sensitive_settings'])->name('receptionist.sensitive-settings')->middleware('auth');

/* Mutual Routes */
// Home - User & Receptionist
Route::get('/home', function () {
    if (!Auth::user()->is_admin() && !Auth::user()->is_receptionist() && session('gym') == null) {
        return redirect()->route('guest.gyms.list');
    }

    if (Auth::user()->is_receptionist()) {
        session('gym', Auth::user()->prefered_gym);
        $gym = Gym::find(session('gym'));
        $enterances = Enterance::all()->where('gym_id', $gym->id)->where('exit', null);

        $tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->sortByDesc('bought')->take(5); // TODO: fix this, not sorting for some reason
        $monthly_tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->sortByDesc('bought')->take(5);

        return view('receptionist.index', ['enterances' => $enterances, 'tickets' => $tickets, 'monthly_tickets' => $monthly_tickets, 'gym' => $gym]);
    } else if (Auth::user()->is_admin()) {

        $gym_name = Gym::all()->pluck('name')->implode(', ');

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
        $active_receptionists = User::all()->filter(function ($user) {
            return $user->is_receptionist();
        });

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
        abort(403);
    } else if (Auth::user()->is_admin()) {
        abort(403);
    } else {
        $gyms = Gym::all();
        $selected_gym_id = Gym::find(session('gym'))->id;
        $current_gym = session('gym');

        return view('user.settings', ['gyms' => $gyms, 'selected_gym_id' => $selected_gym_id, 'current_gym' => $current_gym]);
    }

})->name('settings')->middleware('auth');

Route::get('/settings/sensitive', function () {
    if (Auth::user()->is_receptionist()) {
        return view('receptionist.settings_sensitive');
    } else if (Auth::user()->is_admin()) {
        abort(403);
    } else {
        return view('user.settings_sensitive');
    }

})->name('sensitive-settings')->middleware('auth');

// Purchased monthly tickets - Receptionist & Admin
Route::get('/purchased-monthly', function () {
    if (Auth::user()->is_receptionist()) {
        $gym = Gym::find(session('gym'));
        $purchased_tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->values()->sortByDesc('expiration');

        return view('receptionist.purchased-monthly', ['tickets' => $purchased_tickets, 'gym_name' => $gym->name]);
    } else if (Auth::user()->is_admin()) {
        $gym_name = Gym::all()->pluck('name')->implode(', ');

        $purchased_tickets = Ticket::all()->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->values()
            ->sortByDesc('expiration')
            ->sortByDesc([fn($a, $b) => $a->gym->name <=> $b->gym->name]);

        return view('admin.tickets.index-monthly', ['tickets' => $purchased_tickets, 'gym_name' => $gym_name]);
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
        $gym_name = Gym::all()->pluck('name')->implode(', ');

        $purchased_tickets = Ticket::all()->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->values()->sortByDesc('expiration');

        return view('admin.tickets.index-ticket', ['tickets' => $purchased_tickets, 'gym_name' => $gym_name]);
    } else {
        abort(403);
    }
})->name('purchased-tickets')->middleware('auth');
