<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BuyableTicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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

/* The used auth routes */
Route::get('login', [LoginController::class, 'showLoginForm']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);
Route::get('register', [RegisterController::class, 'showRegistrationForm']);
Route::post('register', [RegisterController::class, 'register']);

/* Admin routes */
Route::get('/categories/{id}/delete', [CategoryController::class, 'delete'])->name('categories.delete')->middleware('auth');
Route::resource('categories', CategoryController::class)->middleware('auth');

Route::get('/buyable-tickets/{id}/hide', [BuyableTicketController::class, 'hide_form'])->name('buyable-tickets.hide.index')->middleware('auth');
Route::patch('/buyable-tickets/{id}/hide', [BuyableTicketController::class, 'hide'])->name('buyable-tickets.hide')->middleware('auth');
Route::resource('buyable-tickets', BuyableTicketController::class, ['only' => ['index', 'create', 'store', 'edit', 'update']])->middleware('auth');

Route::get('/gyms/{id}/delete', [GymController::class, 'delete'])->name('gyms.delete')->middleware('auth');
Route::resource('gyms', GymController::class)->middleware('auth');

Route::get('/tickets/{id}/delete', [TicketController::class, 'delete'])->name('ticket.delete')->middleware('auth');
// Route::delete('/tickets/{id}/delete', [TicketController::class, 'destroy'])->name('ticket.destroy')->middleware('auth');
Route::get('/tickets/edit-ticket/{id}', [TicketController::class, 'edit_ticket'])->name('ticket.edit.index')->middleware('auth');
Route::patch('/tickets/edit-ticket/{id}', [TicketController::class, 'update_ticket'])->name('ticket.edit')->middleware('auth');
Route::get('/tickets/edit-monthly/{id}', [TicketController::class, 'edit_monthly'])->name('monthly-ticket.edit.index')->middleware('auth');
Route::patch('/tickets/edit-monthly/{id}', [TicketController::class, 'update_monthly'])->name('monthly-ticket.edit')->middleware('auth');
Route::resource('tickets', TicketController::class, ['only' => ['destroy']])->middleware('auth');

Route::resource('users', UserController::class, ['only' => ['index', 'edit', 'update']])->middleware('auth');

Route::get('/lockers/{id}/delete', [LockerController::class, 'delete'])->name('lockers.delete')->middleware('auth');
Route::resource('lockers', LockerController::class)->middleware('auth');

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

/* Mutual Routes */
// Home - User & Receptionist
Route::get('/home', function () {
    if (!Gate::allows('admin-action') && !Gate::allows('receptionist-action') && session('gym') == null) {
        return redirect()->route('guest.gyms.list');
    }
    if (Gate::allows('receptionist-action')) {
        if (session('gym') == null) {
            session(['gym' => Auth::user()->prefered_gym]);
        }

        $gym = Gym::find(session('gym'));
        $enterances = Enterance::all()->where('gym_id', $gym->id)->where('exit', null)->sortByDesc('enter')->take(5);

        $tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->sortByDesc('bought')->take(5);
        $monthly_tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->sortByDesc('bought')->take(5);

        return view('receptionist.index', ['enterances' => $enterances, 'tickets' => $tickets, 'monthly_tickets' => $monthly_tickets, 'gym' => $gym]);
    } else if (Gate::allows('admin-action')) {
        $gym_name = Gym::all()->pluck('name')->implode(', ');

        $tickets = Ticket::all()->filter(function ($ticket) {
            return !$ticket->isMonthly();
        })->sortByDesc('bought')->take(5);
        $monthly_tickets = Ticket::all()->filter(function ($ticket) {
            return $ticket->isMonthly();
        })->sortByDesc('bought')->take(5);

        $active_enterances = Enterance::all()->filter(function ($enterance) {
            return !$enterance->exited();
        })->sortByDesc('enter')->take(5);

        return view('admin.index', ['gym_name' => $gym_name, 'tickets' => $tickets, 'monthly_tickets' => $monthly_tickets, 'active_enterances' => $active_enterances]);
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
Route::get('/settings', function () {
    if (Gate::allows('receptionist-action')) {
        abort(403);
    } else if (Gate::allows('admin-action')) {
        abort(403);
    } else {
        $gyms = Gym::all();
        $selected_gym_id = Gym::find(session('gym'))->id;
        $current_gym = session('gym');

        return view('user.settings', ['gyms' => $gyms, 'selected_gym_id' => $selected_gym_id, 'current_gym' => $current_gym]);
    }

})->name('settings')->middleware('auth');

// Purchased monthly tickets - Receptionist & Admin
Route::get('/purchased-monthly', function () {
    if (Gate::allows('receptionist-action')) {
        $gym = Gym::find(session('gym'));
        // $purchased_tickets = Ticket::all()->where('gym_id', $gym->id)->filter(function ($ticket) {
        //     return $ticket->isMonthly();
        // })->values()->sortByDesc('expiration');

        $purchased_tickets = Ticket::where('gym_id', $gym->id)
            ->where('type', 'monthly')
            ->orderBy('expiration', 'desc')
            ->simplePaginate(8);

        return view('receptionist.purchased-monthly', ['tickets' => $purchased_tickets, 'gym_name' => $gym->name]);
    } else if (Gate::allows('admin-action')) {
        $gym_name = Gym::all()->pluck('name')->implode(', ');

        $purchased_tickets = Ticket::where('type', 'monthly')
            ->orderBy('expiration', 'desc')
            ->simplePaginate(8);

        return view('admin.tickets.index-monthly', ['tickets' => $purchased_tickets, 'gym_name' => $gym_name]);
    } else {
        abort(403);
    }
})->name('purchased-monthly')->middleware('auth');

// Purchased tickets - Receptionist & Admin
Route::get('/purchased-tickets', function () {
    if (Gate::allows('receptionist-action')) {
        $gym = Gym::find(session('gym'));

        $purchased_tickets = Ticket::where('gym_id', $gym->id)
            ->where('type', 'one-time')
            ->orderBy('bought', 'desc')
            ->simplePaginate(8);

        return view('receptionist.purchased-tickets', ['tickets' => $purchased_tickets, 'gym_name' => $gym->name]);
    } else if (Gate::allows('admin-action')) {
        $gym_name = Gym::all()->pluck('name')->implode(', ');

        $purchased_tickets = Ticket::where('type', 'one-time')
            ->orderBy('bought', 'desc')
            ->simplePaginate(8);

        return view('admin.tickets.index-ticket', ['tickets' => $purchased_tickets, 'gym_name' => $gym_name]);
    } else {
        abort(403);
    }
})->name('purchased-tickets')->middleware('auth');

// Sensitive settings - Receptionist & User
Route::get('/settings/sensitive', function () {
    if (Gate::allows('receptionist-action')) {
        return view('receptionist.settings_sensitive');
    } else if (Gate::allows('admin-action')) {
        abort(403);
    } else {
        return view('user.settings_sensitive');
    }

})->name('sensitive-settings')->middleware('auth');

Route::patch('/settings/sensitive', function (Request $request) {
    if (Gate::allows('admin-action')) {
        abort(403);
    } else {
        $user = User::all()->where('id', Auth::user()->id)->first();

        $request->validate(
            [
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'password' => [
                    'nullable',
                    'min:8',
                    'max:32',
                    'confirmed',
                ],
                'current_password' => [
                    'required',
                    'current_password',
                ],
            ]
        );

        if ($request['email'] != null) {
            $user->email = $request['email'];
        }
        if ($request['password'] != null) {
            $user->password = Hash::make($request['password']);
        }

        $user->save();

        return redirect()->route('sensitive-settings')->with('success', 'success');
    }

})->name('sensitive-settings')->middleware('auth');
