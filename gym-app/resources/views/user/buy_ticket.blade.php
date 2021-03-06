@extends('layouts.user')
@section('title', 'Jegy/bérlet vásárlása')

@section('content')
  <h2 class="mb-3">Jegy/bérlet vásárlása</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <div class="alert alert-warning text-center" role="alert">
          <h3 class="mb-3">A {{ $ticket_type }} vásárlása 1 hónapra vonatkozik!</h2>
        </div>
        <form action="{{ route('guest.buy-ticket.create', $ticket->id) }}" method="POST">
          @csrf
          <h2 class="mb-3">{{ $ticket_type }} adatai</h2>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="ticketId" class="col-form-label">{{ $ticket_type }} ID</label>
            </div>
            <div class="col-auto">
              <input type="text" id="ticketId" name="ticketId" class="form-control" value="{{ $ticket->id }}"
                disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="ticketName" class="col-form-label">{{ $ticket_type }} neve</label>
            </div>
            <div class="col-auto">
              <input type="text" id="ticketName" name="ticketName" class="form-control" value="{{ $ticket->name }}"
                disabled />
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="ticketDesc" class="col-form-label">{{ $ticket_type }} leírása</label>
            </div>
            <div class="col-5">
              <textarea type="text" id="ticketDesc" name="ticketDesc" class="form-control" rows="4" cols="50"
                disabled>{{ $ticket->description }}</textarea>
            </div>
          </div>
          <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
              <label for="ticketPrice" class="col-form-label"><b>{{ $ticket_type }} ára</b></label>
            </div>
            <div class="col-auto">
              <input type="text" id="ticketPrice" name="ticketPrice" class="form-control"
                value="{{ $ticket->price }} kredit" disabled />
            </div>
          </div>
          <button type="submit" class="btn btn-success">
            Vásárlás
          </button>
        </form>
      </div>
    </div>
  @endsection
