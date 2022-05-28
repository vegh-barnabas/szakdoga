@extends('layouts.receptionist')
@section('title', 'Belépett vendégek')

@section('content')
  <h2 class="mb-3">Belépett vendégek</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym_name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Név</th>
                <th>Felhasznált jegy/bérlet típusa</th>
                <th>Felhasznált jegy/bérlet neve</th>
                <th>Felhasznált jegy/bérlet kódja</th>
                <th>Öltözőszekrény száma</th>
                <th>Belépés időpontja</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($enterances as $enterance)
                <tr>
                  <td>{{ $enterance->user->name }}</td>
                  <td class="text-{{ $enterance->ticket->is_monthly() ? 'primary' : 'success' }}">
                    {{ $enterance->ticket->get_type() }}
                  </td>
                  <td>{{ $enterance->ticket->buyable_ticket->name }}</td>
                  <td>{{ $enterance->ticket->code }}</td>
                  <td>
                    {{ $enterance->locker->number }}
                    ({{ $enterance->locker->gender == 'male' ? 'férfi' : 'női' }} öltöző)
                  </td>
                  <td>{{ $enterance->enter() }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endsection
