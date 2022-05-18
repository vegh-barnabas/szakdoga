@extends('layouts.admin')
@section('title', 'Vásárolt jegy szerkesztése')

@section('content')
  @if (Session::has('success'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted <strong>{{ Session::get('success') }}</strong> jegyet!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Jegy szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $ticket->type->name }} ({{ $ticket->id }}) - {{ $ticket->user->name }}
        ({{ $ticket->user->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('edit-purchased-ticket', $ticket->id) }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Lejárat</label>
              </div>
              <div class="col-auto">
                <input type="date" id="expiration" name="expiration" value="{{ $ticket->expiration }}">
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
                  <select id="used" name="used" class="form-select">
                    <option value="0" {{ !$ticket->useable() ? 'selected' : '' }}>nem</option>
                    <option value="1" {{ $ticket->useable() ? 'selected' : '' }}>igen</option>
                  </select>
                </div>
              </div>
            </div>
          @endif

          <button type="submit" class="btn btn-success">Szerkesztés</button>
        </form>
      </div>
    </div>
  @endsection
