@extends('layouts.admin')
@section('title', 'Megvásárolható jegyek/bérletek')

@section('content')
  @if (Session::has('create'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen létrehoztad a(z) <strong>{{ Session::get('create') }}</strong> megvásárolható jegyet!
    </div>
    </p>
  @endif
  @if (Session::has('edit'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted a(z) <strong>{{ Session::get('edit') }}</strong> megvásárolható jegyet!
    </div>
    </p>
  @endif
  @if (Session::has('hide'))
    <p>
    <div class="alert alert-danger" role="alert">
      Sikeresen {{ Session::get('hide.status') }} a(z) <strong>{{ Session::get('hide.name') }}</strong> megvásárolható
      jegyet!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Megvásárolható jegyek/bérletek</h2>
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
                <th>Elérhető</th>
                <th>Rejtve</th>
                <th>Ár</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($tickets as $ticket)
                <tr>
                  <td>{{ $ticket->gym->name }}</td>
                  <td>{{ $ticket->name }}</td>
                  @if ($ticket->isMonthly())
                    <td class="text-primary"><b>{{ $ticket->get_type() }}</b></td>
                  @else
                    <td class="text-success"><b>{{ $ticket->get_type() }}</b></td>
                  @endif
                  <td>{{ $ticket->description }}</td>
                  <td>{{ $ticket->quantity == 999 ? 'Végtelen' : $ticket->quantity }}</td>
                  <td>{{ $ticket->hidden ? 'igen' : 'nem' }}</td>
                  <td>{{ $ticket->price }}</td>
                  <td>
                  <td>
                    <a href="{{ route('buyable-tickets.edit', $ticket->id) }}" class="link-primary">
                      <x-ri-edit-fill class="icon" style="height: 22px" />
                    </a>
                    <a href="{{ route('buyable-tickets.hide', $ticket->id) }}" class="link-primary">
                      <x-bi-eye-fill class="icon" style="height: 22px" />
                    </a>
                  </td>
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

      <a href="{{ route('buyable-tickets.create') }}" class="btn btn-primary">Új jegy</a>
    </div>
  @endsection
