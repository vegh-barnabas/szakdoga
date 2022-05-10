@extends('layouts.admin')
@section('title', 'Megvásárolható jegyek/bérletek')

@section('content')
  <h2 class="mb-3">Megvásárolható jegyek/bérletek</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym_name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <table class="table">
          <thead>
            <tr>
              <th>Edzőterem</th>
              <th>Név</th>
              <th>Típus</th>
              <th>Leírás</th>
              <th>Elérhető</th>
              <th>Ár</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tickets as $ticket)
              <tr>
                <td>{{ $ticket->gym->name }}</td>
                <td class="text-{{ $ticket->isMonthly() ? 'primary' : 'success' }}">{{ $ticket->name }}</td>
                <td>{{ $ticket->type }}</td>
                <td>{{ $ticket->description }}</td>
                <td>{{ $ticket->quantity == 999 ? 'Végtelen' : $ticket->quantity }}</td>
                <td>{{ $ticket->price }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endsection
