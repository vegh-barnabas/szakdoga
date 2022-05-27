@extends('layouts.user')
@section('title', 'Statisztika')

@section('content')
  <h2>Statisztika</h2>
  <p>Ezen az oldalon tudsz az edzéseidről és az edzőteremről statisztikákat nézni.</p>
  <div class="row">
    <div class="col p-4">
      <div class="row">
        <div class="col-lg-6 mb-3">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">{{ $gym->name }}</h5>
              <h6 class="card-subtitle text-muted">Statisztika - látogatásaid</h6>
            </div>
            <div class="card-body">
              <div class="card-text">
                <h5>Látogatásaid</h5>

                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Dátum</th>
                        <th>Mettől</th>
                        <th>Meddig</th>
                        <th>Időtartam</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach (Auth::user()->enterances as $enterance)
                        @if ($enterance->gym_id == $gym->id)
                          <tr>
                            <td>{{ date_create($enterance->enter)->format('Y. m. d') }}</td>
                            <td>{{ date_create($enterance->enter)->format('H:i') }}</td>
                            <td>{{ date_create($enterance->exit)->format('H:i') }}</td>
                            <td>
                              {{ date_create($enterance->exit)->diff(date_create($enterance->enter))->format('%h óra %i perc') }}
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <h6>Átlagban {{ $hour_avg }} órát és {{ $min_avg }} percet tartózkodsz az edzőteremben.</h6>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">{{ $gym->name }}</h5>
              <h6 class="card-subtitle text-muted">Statisztika - edzőterem mai látogatottsága</h6>
            </div>
            <div class="card-body">
              <div class="card-text">
                <h4>Ma <b>{{ $enterance_count }}</b> vendég lépett be az edzőterembe.</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
