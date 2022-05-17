@extends('layouts.admin')
@section('title', 'Felhasználó szerkesztése')

@section('content')
  <h2 class="mb-3">Kategória törlése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $category->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('delete-category', $category->id) }}" method="POST">
          @method('delete')
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Név</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control" value="{{ $category->name }}"
                  disabled />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="style" class="col-form-label">Stílus</label>
              </div>
              <div class="col-auto">
                <select id="style" name="style" class="form-select" disabled>
                  @foreach ($styles as $style)
                    <option value="{{ $style }}" {{ $category->style == $style ? 'selected' : '' }}>
                      {{ $style }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-danger">Törlés</button>
        </form>
      </div>
    </div>
  </div>
@endsection
