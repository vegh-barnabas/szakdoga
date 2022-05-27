@extends('layouts.user')
@section('title', 'Jegyek és bérletek listája')

@section('content')
  @if (Session::has('error-not-enough-credits'))
    <p>
    <div class="alert alert-danger" role="alert">
      Nincs elég kredited a(z) <strong>{{ Session::get('error-not-enough-credits.ticket-name') }}</strong> nevű bérleted
      meghosszabbításához!
    </div>
    </p>
  @endif

  <h2>Jegyek, bérletek</h2>
  <p>Ezen az oldalon tudod megnézni az összes jegyed és bérleted.</p>
  <div class="row">
    <div class="col p-4">
      <div class="row">
        <div class="col">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Felhasználható jegyek/bérletek</h5>
                <h6 class="card-subtitle text-muted mb-2">
                  {{ $gym->name }}
                </h6>
                <p class="card-text">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Név</th>
                      <th scope="col">Típus</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                      <th scope="col">Lehetőség</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tickets as $ticket)
                      @if ($ticket->useable())
                        <tr>
                          <td>{{ $ticket->get_type() }}</td>
                          @if ($ticket->isMonthly())
                            <td class="text-primary"><b>{{ $ticket->get_type() }}</b></td>
                          @else
                            <td class="text-success"><b>{{ $ticket->get_type() }}</b></td>
                          @endif
                          <td>{{ $ticket->expiration() }}</td>
                          @if ($ticket->expired())
                            <td class="text-danger">Lejárt</td>
                            <td><button type="submit" class="btn btn-light">Hosszabbítás</button></td>
                          @else
                            <td class="text-success">Aktív</td>
                            <td>
                              <p>
                                @if ($ticket->isMonthly())
                                  <button class="btn btn-primary" type="submit" data-bs-toggle="collapse"
                                    data-bs-target="{{ '#' . Str::slug($ticket->buyable_ticket->name . $ticket->id) }}"
                                    aria-expanded="false" aria-controls="{{ $ticket->id }}">
                                    Belépési kód
                                  </button>
                                @else
                                  <button class="btn btn-success" type="submit" data-bs-toggle="collapse"
                                    data-bs-target="{{ '#' . Str::slug($ticket->buyable_ticket->name . $ticket->id) }}"
                                    aria-expanded="false" aria-controls="{{ $ticket->id }}">
                                    Belépési kód
                                  </button>
                                @endif
                              </p>
                              <div class="collapse"
                                id="{{ Str::slug($ticket->buyable_ticket->name . $ticket->id) }}">
                                <div class="card card-body">
                                  {{ $ticket->code }}
                                </div>
                              </div>
                            </td>
                          @endif
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Lejárt bérletek</h5>
                <h6 class="card-subtitle text-muted mb-2">
                  {{ $gym->name }}
                </h6>
                <p class="card-text">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Név</th>
                      <th scope="col">Típus</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                      <th scope="col">Lehetőség</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tickets as $ticket)
                      @if ($ticket->isMonthly() && !$ticket->useable())
                        <tr>
                          <form action="{{ route('guest.extend-ticket.show', $ticket->id) }}" method="GET">
                            <td>{{ $ticket->buyable_ticket->name }}</td>
                            @if ($ticket->isMonthly())
                              <td class="text-primary"><b>{{ $ticket->get_type() }}</b></td>
                            @else
                              <td class="text-success"><b>{{ $ticket->get_type() }}</b></td>
                            @endif
                            <td>{{ $ticket->expiration() }}</td>
                            @if ($ticket->expired())
                              <td class="text-danger">Lejárt</td>
                              <td><button type="submit" class="btn btn-light">Hosszabbítás</button></td>
                            @else
                              <td class="text-success">Aktív</td>
                              <td>
                                <p>
                                  <button class="btn btn-success" type="submit" data-bs-toggle="collapse"
                                    data-bs-target="{{ '#' . Str::slug($ticket->buyable_ticket->name . $ticket->id) }}"
                                    aria-expanded="false" aria-controls="{{ $ticket->id }}">
                                    Belépési kód
                                  </button>
                                </p>
                                <div class="collapse"
                                  id="{{ Str::slug($ticket->buyable_ticket->name . $ticket->id) }}">
                                  <div class="card card-body">
                                    {{ $ticket->code }}
                                  </div>
                                </div>
                              </td>
                            @endif
                          </form>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
