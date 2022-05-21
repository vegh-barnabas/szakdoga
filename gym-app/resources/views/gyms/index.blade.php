@extends('layouts.app')
@section('title', 'Edzőtermek')

@section('content')
  <h1 class="mb-4">Válassz edzőtermet</h1>
  <div class="row justify-content-center">
    @foreach ($gyms as $gym)
      <div class="col">
        <form method="POST">
          @csrf
          <div class="card">
            <img src="img/gmaps.png" class="card-img-top" alt="" />
            <div class="card-body">
              <h5 class="card-title mb-2">{{ $gym->name }}</h5>
              <h6 class="card-subtitle mb-2 text-muted">{{ $gym->address }}</h6>
              <div class="categories mb-2">
                @foreach ($gym->categories as $category)
                  <span class="badge rounded-pill bg-{{ $category->style }}">{{ $category->name }}</span>
                @endforeach
              </div>
              <div class="mb-4">
                <p class="card-text">{{ $gym->description }}</p>
              </div>
              <input type="hidden" id="gymId" name="gymId" value="{{ $loop->iteration }}">
              <button class="btn btn-success">Tovább</a>
            </div>
          </div>
        </form>
      </div>
    @endforeach
  @endsection
