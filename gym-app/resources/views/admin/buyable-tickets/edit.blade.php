@extends('layouts.admin')
@section('title', 'Megvásárolható jegy szerkesztése')

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

  <h2 class="mb-3">Megvásárolható jegy szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $ticket->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('buyable-tickets.update', $ticket->id) }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="gym_id" class="col-form-label">Edzőterem</label>
              </div>
              <div class="col-auto">
                <select id="gym_id" name="gym_id" class="form-select">
                  @foreach ($gyms as $gym)
                    <option value="{{ $gym->id }}"
                      {{ (old('gym_id') == $gym->id ? 'selected' : $gym->id == $ticket->gym->id && !old('gym_id')) ? 'selected' : '' }}>
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
                <input type="text" id="name" name="name" class="form-control"
                  value="{{ old('name') ?? $ticket->name }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="type" class="col-form-label">Típus</label>
              </div>
              <div class="col-auto">
                <select id="type" name="type" class="form-select">
                  <option value="one-time"
                    {{ (old('type') == 'one-time' ? 'selected' : !$ticket->isMonthly() && !old('type')) ? 'selected' : '' }}>
                    jegy
                  </option>
                  <option value="monthly"
                    {{ (old('type') == 'monthly' ? 'selected' : $ticket->isMonthly() && !old('type')) ? 'selected' : '' }}>
                    bérlet
                  </option>
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
                <textarea id="description" name="description" rows="4"
                  cols="50">{{ old('description') ?? $ticket->description }}</textarea>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="quantity" class="col-form-label">Elérhető</label>
              </div>
              <div class="col-auto">
                <input type="number" id="quantity" name="quantity" class="form-control" aria-describedby="quantity_help"
                  value="{{ old('quantity') ?? $ticket->quantity }}" />
              </div>
              <div class="col-auto">
                <span id="quantity_help" class="form-text">Végtelen mennyiséghez adj meg 999-et.</span>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="price" class="col-form-label">Ár</label>
              </div>
              <div class="col-auto">
                <input type="number" id="price" name="price" class="form-control"
                  value="{{ old('price') ?? $ticket->price }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="price" class="col-form-label">Rejtett</label>
              </div>
              <div class="col-auto">
                <input type="radio" id="false" name="hidden" value="0"
                  {{ (old('hidden') ? 'checked' : !$ticket->hidden) ? 'checked' : '' }}>
                <label for="false">nem</label>
                <input type="radio" id="true" name="hidden" value="1"
                  {{ (old('hidden') ? 'checked' : $ticket->hidden) ? 'checked' : '' }}>
                <label for="true">igen</label>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Szerkesztés</button>
        </form>
      </div>
    </div>
  @endsection
