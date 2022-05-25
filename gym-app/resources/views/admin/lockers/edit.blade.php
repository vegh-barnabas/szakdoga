@extends('layouts.admin')
@section('title', 'Szekrény szerkesztése')

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

  <h2 class="mb-3">Szekrény szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $locker->number }} számú szekrény (ID {{ $locker->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('lockers.update', $locker->id) }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="number" class="col-form-label">Szám</label>
              </div>
              <div class="col-auto">
                <input type="number" id="number" name="number" class="form-control" value="{{ $locker->number }}" />
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

          <button type="submit" class="btn btn-success">Szerkesztés</button>
        </form>
      </div>
    </div>
  </div>
@endsection
