@extends('layouts.admin')
@section('title', 'Edzőterem törlése')

@section('content')

  <h2 class="mb-3">Edzőterem törlése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('gyms.destroy', $gym->id) }}" method="POST">
          @csrf
          @method('delete')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Név</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control" value="{{ $gym->name }}" disabled />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="address" class="col-form-label">Cím</label>
              </div>
              <div class="col-auto">
                <input type="text" id="address" name="address" class="form-control" value="{{ $gym->address }}"
                  disabled />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="description" class="col-form-label">Leírás</label>
              </div>
              <div class="col-auto">
                <textarea id="description" name="description" rows="4" cols="50" disabled>{{ $gym->description }}</textarea>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="categories" class="col-form-label">Kategóriák</label>
              </div>
              <div class="col-auto">
                @foreach ($gym->categories as $category)
                  <span class="badge rounded-pill bg-{{ $category->style }}">{{ $category->name }}</span>
                  <br>
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
