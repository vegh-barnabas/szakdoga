@extends('layouts.admin')
@section('title', 'Megvásárolt jegyek')

@section('content')
  @if (Session::has('delete'))
    <p>
    <div class="alert alert-danger" role="alert">
      Sikeresen törölted a(z) <strong>{{ Session::get('delete') }}</strong> jegyet!
    </div>
    </p>
  @endif
  @if (Session::has('edit'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted a(z) <strong>{{ Session::get('edit') }}</strong> jegyet!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Megvásárolt jegyek</h2>
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
              <th>Felhasználó</th>
              <th>Státusz</th>
              <th>Kód</th>
              <th>Megvásárolva</th>
              <th>Lejárat</th>
              <th>Opciók</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tickets as $ticket)
              <tr>
                <td>{{ $ticket->gym->name }}</td>
                <td>{{ $ticket->type->name }}</td>
                <td class="text-success"><b>{{ $ticket->get_type() }}</b></td>
                <td>{{ $ticket->type->description }}</td>
                <td>{{ $ticket->user->name }} (ID {{ $ticket->user->id }})</td>
                @if ($ticket->useable())
                  <td>Felhasználható</td>
                @elseif($ticket->used())
                  <td>Felhasznált</td>
                @else
                  <td>Lejárt</td>
                @endif
                <td>{{ $ticket->code }}</td>
                <td>{{ $ticket->bought() }}</td>
                <td>{{ $ticket->expiration() }}</td>
                <td>
                  <a href="{{ route('ticket.edit', $ticket->id) }}" class="link-primary">
                    <x-ri-edit-fill class="icon" style="height: 22px" />
                  </a>
                  <a href="{{ route('ticket.delete', $ticket->id) }}" class="link-primary">
                    <x-ri-delete-back-2-fill class="icon" style="height: 22px" />
                  </a>
                </td>
              </tr>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endsection
