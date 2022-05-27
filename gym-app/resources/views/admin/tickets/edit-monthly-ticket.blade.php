@extends('layouts.admin')
@section('title', 'Bérlet szerkesztése')

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

  <h2 class="mb-3">Bérlet szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $ticket->buyable_ticket->name }} ({{ $ticket->id }}) - {{ $ticket->user->name }}
        ({{ $ticket->user->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('monthly-ticket.edit', $ticket->id) }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="bought" class="col-form-label">Érvényesség kezdete</label>
              </div>
              <div class="col-auto">
                <input type="date" id="bought" name="bought" value="{{ old('bought') ?? $ticket->bought }}">
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="expiration" class="col-form-label">Érvényesség vége</label>
              </div>
              <div class="col-auto">
                <input type="date" id="expiration" name="expiration"
                  value="{{ old('expiration') ?? $ticket->expiration }}">
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Szerkesztés</button>
        </form>
      </div>
    </div>
  </div>
@endsection
