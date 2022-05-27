@extends('layouts.admin')
@section('title', 'Edzőterem létrehozása')

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

  <h2 class="mb-3">Edzőterem létrehozása</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('gyms.store') }}" method="POST">
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
                <label for="address" class="col-form-label">Cím</label>
              </div>
              <div class="col-auto">
                <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="description" class="col-form-label">Leírás</label>
              </div>
              <div class="col-auto">
                <input type="text" id="description" name="description" class="form-control"
                  value="{{ old('description') }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="categories" class="col-form-label">Kategóriák</label>
              </div>
              <div class="col-auto">
                @php
                  $checked_categories = old('categories', $post->categories->pluck('id')->toArray());
                @endphp
                @foreach ($categories as $category)
                  <input type="checkbox" id="{{ $category->id }}" name="categories[]" value="{{ $category->id }}"
                    @if (is_array($checked_categories) && in_array($category->id, $checked_categories)) checked @endif>

                  <label for="{{ $category->id }}">
                    <span class="badge rounded-pill bg-{{ $category->style }}">{{ $category->name }}</span>
                  </label>
                  <br>
                @endforeach
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Létrehozás</button>
        </form>
      </div>
    </div>
  </div>
@endsection
