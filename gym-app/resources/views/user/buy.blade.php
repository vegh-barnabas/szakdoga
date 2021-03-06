@extends('layouts.user')
@section('title', 'Jegy/bérlet vásárlás')

@section('content')
  @if (Session::has('error'))
    <p>
    <div class="alert alert-danger" role="alert">
      Nincs elég kredited a jegy megvásárlására!
    </div>
    </p>
  @endif

  <h2>Jegy/bérlet vásárlás</h2>
  <p>Ezen az oldalon tudsz a kreditedből jegyeket és bérleteket vásárolni. Vigyázz, melyiket választod ki, mert a vásárlás
    után nem tudod már visszamondani a terméket.</p>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
      <h6 class="card-subtitle text-muted">elérhető jegyek/bérletek</h6>
    </div>
    <div class="card-body">
      <div class="card-text">
        <h5 class="mb-3">Elérhető kreditek: <b>{{ Auth::user()->credits }}</b></h5>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Név</th>
                <th>Típus</th>
                <th>Megjegyzés</th>
                <th>Elérhető</th>
                <th>Ár</th>
                <th>Vásárlás/hosszabbítás</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($buyable_tickets as $ticket)
                {{-- existing bérlet --}}
                @if (Auth::user()->tickets->where('buyable_ticket_id', $ticket->id)->count() > 0 && $ticket->is_monthly())
                  <form action="{{ route('guest.extend-ticket.show', $ticket->id) }}" method="GET">
                    <tr>
                      <td>{{ $ticket->name }}</td>
                      <td>{{ $ticket->get_type() }}</td>
                      <td>{{ $ticket->description }}</td>
                      @if ($ticket->quantity == 999)
                        <td>végtelen</td>
                      @else
                        <td>{{ $ticket->quantity }}</td>
                      @endif
                      @if ($ticket->price == 0)
                        <td>ingyenes</td>
                      @else
                        <td>{{ $ticket->price }}</td>
                      @endif
                      <td>
                        @if (Auth::user()->tickets->where('buyable_ticket_id', $ticket->id)->first()->expired())
                          <a href="{{ route('guest.extend-ticket.show',Auth::user()->tickets->where('buyable_ticket_id', $ticket->id)->first()) }}"
                            class="btn btn-primary">Hosszabbítás</a>
                        @endif
                      </td>
                    </tr>
                  </form>
                @endif
              @endforeach
              @foreach ($buyable_tickets as $ticket)
                {{-- existing ticket --}}
                @if (Auth::user()->tickets->where('buyable_ticket_id', $ticket->id)->count() > 0 && !$ticket->is_monthly())
                  <form action="{{ route('guest.buy-ticket.show', $ticket->id) }}" method="GET">
                    <tr>
                      <td>{{ $ticket->name }}</td>
                      <td>{{ $ticket->get_type() }}</td>
                      <td>{{ $ticket->description }}</td>
                      @if ($ticket->quantity == 999)
                        <td>végtelen</td>
                      @else
                        <td>{{ $ticket->quantity }}</td>
                      @endif
                      @if ($ticket->price == 0)
                        <td>ingyenes</td>
                      @else
                        <td>{{ $ticket->price }}</td>
                      @endif
                      <td>
                        <button class="btn btn-success">Vásárlás</button>
                      </td>
                    </tr>
                  </form>
                @endif
              @endforeach
              @foreach ($buyable_tickets as $ticket)
                {{-- non-existing bérlet --}}
                @if (!Auth::user()->tickets->where('buyable_ticket_id', $ticket->id)->count() > 0 && $ticket->is_monthly())
                  <form action="{{ route('guest.buy-ticket.show', $ticket->id) }}" method="GET">
                    <tr>
                      <td>{{ $ticket->name }}</td>
                      <td>{{ $ticket->get_type() }}</td>
                      <td>{{ $ticket->description }}</td>
                      @if ($ticket->quantity == 999)
                        <td>végtelen</td>
                      @else
                        <td>{{ $ticket->quantity }}</td>
                      @endif
                      @if ($ticket->price == 0)
                        <td>ingyenes</td>
                      @else
                        <td>{{ $ticket->price }}</td>
                      @endif
                      <td>
                        <button class="btn btn-success">Vásárlás</button>
                      </td>
                    </tr>
                  </form>
                @endif
              @endforeach
              @foreach ($buyable_tickets as $ticket)
                {{-- non-existing ticket --}}
                @if (!Auth::user()->tickets->where('buyable_ticket_id', $ticket->id)->count() > 0 && !$ticket->is_monthly())
                  <form action="{{ route('guest.buy-ticket.show', $ticket->id) }}" method="GET">
                    <tr>
                      <td>{{ $ticket->name }}</td>
                      <td>{{ $ticket->get_type() }}</td>
                      <td>{{ $ticket->description }}</td>
                      @if ($ticket->quantity == 999)
                        <td>végtelen</td>
                      @else
                        <td>{{ $ticket->quantity }}</td>
                      @endif
                      @if ($ticket->price == 0)
                        <td>ingyenes</td>
                      @else
                        <td>{{ $ticket->price }}</td>
                      @endif
                      <td>
                        <button class="btn btn-success">Vásárlás</button>
                      </td>
                    </tr>
                  </form>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
