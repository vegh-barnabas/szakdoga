@extends('layouts.receptionist')
@section('title', 'Beléptetés')

@section('content')
  <h2 class="mb-3">Vendég beléptetése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Harap utcai edzőterem</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('let-in-2', $code, $user, $ticket, $lockers) }}" method="POST">
          @csrf
          <h2 class="mb-3">Vendég adatai</h2>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="userName" class="col-form-label">Felhasználó neve</label>
            </div>
            <div class="col-auto">
              <input type="text" id="userName" name="userName" class="form-control" value="{{ $user->name }}"
                disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="userGender" class="col-form-label">Felhasználó neme</label>
            </div>
            <div class="col-auto">
              <input type="text" id="userGender" name="userGender" class="form-control"
                value="{{ $user->gender == 0 ? 'férfi' : 'nő' }}" disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="usedTicket" class="col-form-label">Felhasználandó bérlet/jegy</label>
            </div>
            <div class="col-auto">
              <input type="text" id="usedTicket" name="usedTicket" class="form-control"
                value="{{ $ticket->type->name }} ({{ $ticket->id }})" disabled />
            </div>
          </div>
          <h2 class="mb-3">Beállítandó adatok</h2>
          {{-- <div class="mb-3">
            <label for="locker" class="form-label">Válassz szekrényt! ({{ $user->gender == 0 ? 'férfi' : 'női' }}
              öltöző)</label>
            <select class="form-select" id="locker" name="locker">
              @foreach ($lockers as $locker)
                <option value="{{ $locker->number }}">{{ $locker->number }}</option>
              @endforeach
            </select>
          </div> --}}
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="keyGiven" name="keyGiven" />
            <label class="form-check-label" for="keyGiven"><b class="text-danger">A lakatot és a kulcsot
                odaadtam</b></label>
          </div>

          <button type="submit" class="btn btn-success">
            Vendég beléptetése
          </button>
        </form>
      </div>
    </div>
  @endsection
