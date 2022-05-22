@extends('layouts.admin')
@section('title', 'Kategória létrehozása')

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

  <h2 class="mb-3">Kategória létrehozása</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('categories.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Név</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="style" class="col-form-label">Stílus</label>
              </div>
              <div class="col-auto">
                <select id="style" name="style" class="form-select">
                  @foreach ($styles as $style)
                    <option value="{{ $style }}" {{ old('name') == $style ? 'selected' : '' }}>
                      {{ $style }}
                    </option>
                  @endforeach
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
