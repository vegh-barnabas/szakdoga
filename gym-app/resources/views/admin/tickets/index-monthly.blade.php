@extends('layouts.admin')
@section('title', 'Megvásárolt bérletek')

@section('content')
  @if (Session::has('delete'))
    <p>
    <div class="alert alert-danger" role="alert">
      Sikeresen törölted a(z) <strong>{{ Session::get('delete') }}</strong> bérletet!
    </div>
    </p>
  @endif
  @if (Session::has('edit'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted a(z) <strong>{{ Session::get('edit') }}</strong> bérletet!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Megvásárolt bérletek</h2>
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
                  <td>{{ $ticket->buyable_ticket->name }}</td>
                  <td class="text-primary"><b>{{ $ticket->get_type() }}</b></td>
                  <td>{{ $ticket->buyable_ticket->description }}</td>
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
                    <a href="{{ route('monthly-ticket.edit', $ticket->id) }}" class="link-primary">
                      <x-ri-edit-fill class="icon" style="height: 22px" />
                    </a>
                    <a href="{{ route('ticket.delete', $ticket->id) }}" class="link-primary">
                      <x-ri-delete-back-2-fill class="icon" style="height: 22px" />
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="d-flex justify-content-center">
            {{ $tickets->links() }}
          </div>
        </div>
      </div>
    </div>
  @endsection
