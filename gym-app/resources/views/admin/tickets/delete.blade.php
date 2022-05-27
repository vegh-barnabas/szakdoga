@extends('layouts.admin')
@section('title', 'Jegy törlése')

@section('content')
  <h2 class="mb-3">{{ $ticket->isMonthly() ? 'Bérlet' : 'Jegy' }} törlése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $ticket->buyable_ticket->name }} ({{ $ticket->id }}) - {{ $ticket->user->name }}
        ({{ $ticket->user->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
          @csrf
          @method('delete')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="guest" class="col-form-label">Vendég</label>
              </div>
              <div class="col-auto">
                <input type="text" id="guest" name="guest"
                  value="{{ $ticket->user->name }} (ID {{ $ticket->user->id }})" disabled>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="code" class="col-form-label">Kód</label>
              </div>
              <div class="col-auto">
                <input type="text" id="code" name="code" value="{{ $ticket->code }}" disabled>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="expiration" class="col-form-label">Megvásárolva</label>
              </div>
              <div class="col-auto">
                <input type="text" id="expiration" name="expiration" value="{{ $ticket->bought }}" disabled>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="expiration" class="col-form-label">Lejárat</label>
              </div>
              <div class="col-auto">
                <input type="text" id="expiration" name="expiration" value="{{ $ticket->expiration }}" disabled>
              </div>
            </div>
          </div>
          @if (!$ticket->expired())
            <div class="mb-3">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="name" class="col-form-label">Felhasználható</label>
                </div>
                <div class="col-auto">
                  <select id="used" name="used" class="form-select" disabled>
                    <option value="0" {{ !$ticket->useable() ? 'selected' : '' }}>nem</option>
                    <option value="1" {{ $ticket->useable() ? 'selected' : '' }}>igen</option>
                  </select>
                </div>
              </div>
            </div>
          @endif

          <button type="submit" class="btn btn-danger">Törlés</button>
        </form>
      </div>
    </div>
  @endsection
