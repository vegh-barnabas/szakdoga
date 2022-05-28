@extends('layouts.admin')
@section('title', 'Megvásárolható jegy/bérlet elrejtése/megjelenítése')

@section('content')
  <h2 class="mb-3">Megvásárolható jegy/bérlet {{ $ticket->hidden ? 'megjelenítése' : 'elrejtése' }}</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $ticket->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('buyable-tickets.hide', $ticket->id) }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="gym_id" class="col-form-label">Edzőterem</label>
              </div>
              <div class="col-auto">
                <select id="gym_id" name="gym_id" class="form-select" disabled>
                  @foreach ($gyms as $gym)
                    <option value="{{ $gym->id }}" {{ $ticket->gym_id == $gym->id ? 'selected' : '' }}>
                      {{ $gym->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Név</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control" disabled value="{{ $ticket->name }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="type" class="col-form-label">Típus</label>
              </div>
              <div class="col-auto">
                <select id="type" name="type" class="form-select" disabled>
                  <option value="one-time" {{ !$ticket->is_monthly() ? 'selected' : '' }}>jegy</option>
                  <option value="monthly" {{ $ticket->is_monthly() ? 'selected' : '' }}>bérlet</option>
                </select>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="description" class="col-form-label">Leírás</label>
              </div>
              <div class="col-auto">
                <textarea id="description" name="description" rows="4" cols="50" disabled>{{ $ticket->description }}</textarea>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="quantity" class="col-form-label">Elérhető</label>
              </div>
              <div class="col-auto">
                <input type="number" id="quantity" name="quantity" class="form-control"
                  value="{{ $ticket->quantity }}" disabled />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="price" class="col-form-label">Ár</label>
              </div>
              <div class="col-auto">
                <input type="number" id="price" name="price" class="form-control" value="{{ $ticket->price }}"
                  disabled />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="price" class="col-form-label">Rejtett</label>
              </div>
              <div class="col-auto">
                <input type="radio" id="false" name="hidden" value="0" {{ !$ticket->hidden ? 'checked' : '' }}
                  disabled>
                <label for="false">nem</label>
                <input type="radio" id="true" name="hidden" value="1" {{ $ticket->hidden ? 'checked' : '' }} disabled>
                <label for="true">igen</label>
              </div>
            </div>
          </div>

          @if ($ticket->hidden)
            <button type="submit" class="btn btn-secondary">Megjelenítés</button>
          @else
            <button type="submit" class="btn btn-secondary">Elrejtés</button>
          @endif
        </form>
      </div>
    </div>
  @endsection
