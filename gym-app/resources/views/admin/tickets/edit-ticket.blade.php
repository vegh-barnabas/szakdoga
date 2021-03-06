@extends('layouts.admin')
@section('title', 'Megvásárolt jegy szerkesztése')

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

  <h2 class="mb-3">Megvásárolt jegy szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $ticket->buyable_ticket->name }} {{ $ticket->get_type() }} (ID {{ $ticket->id }})
        -
        {{ $ticket->user->name }} vendég
        (ID {{ $ticket->user->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('ticket.edit', $ticket->id) }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Lejárat</label>
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
  @endsection
