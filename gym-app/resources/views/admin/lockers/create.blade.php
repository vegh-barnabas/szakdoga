@extends('layouts.admin')
@section('title', 'Szekrény létrehozása')

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

  <h2 class="mb-3">Szekrény létrehozása</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('lockers.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="gym_id" class="col-form-label">Edzőterem</label>
              </div>
              <div class="col-auto">
                <select id="gym_id" name="gym_id" class="form-select">
                  @foreach ($gyms as $gym)
                    <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="number" class="col-form-label">Szám</label>
              </div>
              <div class="col-auto">
                <input type="number" id="number" name="number" class="form-control" />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="gender" class="col-form-label">Nem</label>
              </div>
              <div class="col-auto">
                <select id="gender" name="gender" class="form-select">
                  <option value="male">férfi</option>
                  <option value="female">nő</option>
                </select>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Létrehozás</button>
        </form>
      </div>
    </div>
  </div>
@endsection
