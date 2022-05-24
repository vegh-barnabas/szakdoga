@extends('layouts.admin')
@section('title', 'Szekrény törlése')

@section('content')

  <h2 class="mb-3">Szekrény törlése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $locker->number }} ({{ $locker->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('lockers.destroy', $locker->id) }}" method="POST">
          @csrf
          @method('delete')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="gym-name" class="col-form-label">Edzőterem</label>
              </div>
              <div class="col-auto">
                <input type="text" id="gym-name" name="gym-name" class="form-control" value="{{ $locker->gym->name }}"
                  disabled />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="id" class="col-form-label">ID</label>
              </div>
              <div class="col-auto">
                <input type="number" id="id" name="id" class="form-control" value="{{ $locker->id }}" disabled />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="number" class="col-form-label">Szám</label>
              </div>
              <div class="col-auto">
                <input type="number" id="number" name="number" class="form-control" value="{{ $locker->number }}"
                  disabled />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="gender" class="col-form-label">Nem</label>
              </div>
              <div class="col-auto">
                <input type="text" id="gender" name="gender" class="form-control"
                  value="{{ $locker->gender == 'male' ? 'férfi' : 'nő' }}" disabled />
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-danger">Törlés</button>
        </form>
      </div>
    </div>
  </div>
@endsection
