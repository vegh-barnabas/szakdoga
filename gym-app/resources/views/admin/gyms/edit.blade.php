@extends('layouts.admin')
@section('title', 'Edzőterem szerkesztése')

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

  <h2 class="mb-3">Edzőterem szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('gyms.update', $gym->id) }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Név</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control"
                  value="{{ old('name') ?? $gym->name }}" />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="address" class="col-form-label">Cím</label>
              </div>
              <div class="col-auto">
                <input type="text" id="address" name="address" class="form-control"
                  value="{{ old('address') ?? $gym->address }}" />
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
                  cols="50">{{ old('description') ?? $gym->description }}</textarea>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="categories" class="col-form-label">Kategóriák</label>
              </div>
              <div class="col-auto">
                @foreach ($categories as $category)
                  <input type="checkbox" id="{{ $category->name }}" name="categories[]" value="{{ $category->id }}"
                    @if (is_array(old('categories')) && in_array($category->id, old('categories'))) checked @endif>

                  <label for="{{ $category->name }}">
                    <span class="badge rounded-pill bg-{{ $category->style }}">{{ $category->name }}</span>
                  </label>
                  <br>
                @endforeach
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
