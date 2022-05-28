@extends('layouts.receptionist')
@section('title', 'Kiléptetés')

@section('content')
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <h2 class="mb-3">Vendég kiléptetése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('receptionist.let-out', $user->exit_code) }}" method="POST">
          @csrf
          <h2 class="mb-3">Vendég adatai</h2>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="enterance-code" class="col-form-label">Felhasználó neve</label>
            </div>
            <div class="col-auto">
              <input type="text" id="enterance-code" class="form-control" value="{{ $user->name }}" disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="enteranceCode" class="col-form-label">Felhasználó neme</label>
            </div>
            <div class="col-auto">
              <input type="text" id="enteranceCode" class="form-control"
                value="{{ $user->gender == 'male' ? 'férfi' : 'nő' }}" disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="usedTicket" class="col-form-label">Felhasznált bérlet/jegy</label>
            </div>
            <div class="col-auto">
              <input type="text" id="usedTicket" class="form-control"
                value="{{ $enterance->ticket->buyable_ticket->name }} ({{ $enterance->ticket->id }})" disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="usedTicket" class="col-form-label">Belépés időpontja</label>
            </div>
            <div class="col-auto">
              <input type="text" id="usedTicket" class="form-control" value="{{ $enterance->enter() }}" disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="locker" class="col-form-label">Szekrény</label>
            </div>
            <div class="col-auto">
              <input type="text" id="locker" name="locker" class="form-control"
                value="{{ $enterance->get_locker()->number }} ({{ $enterance->get_locker()->gender == 'male' ? 'férfi' : 'nő' }})"
                disabled />
            </div>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="key_given" name="key_given" />
            <label class="form-check-label" for="key_given"><b class="text-danger">A lakatot és a kulcsot
                visszakaptam</b></label>
          </div>

          <button type="submit" class="btn btn-danger">
            Vendég kiléptetése
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
