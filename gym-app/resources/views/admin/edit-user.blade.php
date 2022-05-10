@extends('layouts.admin')
@section('title', 'Felhasználó szerkesztése')

@section('content')
  @if (Session::has('success'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted <strong>{{ Session::get('success') }}</strong> felhasználót!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Felhasználó szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $user->name }} (ID: {{ $user->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('let-in') }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Név</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control" />
                @error('name')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="email" class="col-form-label">E-mail cím</label>
              </div>
              <div class="col-auto">
                <input type="text" id="email" name="email" class="form-control" value="{{ $user->email }}" />
                @error('email')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="email" class="col-form-label">Nem</label>
              </div>
              <div class="col-auto">
                <input type="text" id="email" name="email" class="form-control" value="{{ $user->gender }}" />
                @error('email')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="email" class="col-form-label">Kreditek</label>
              </div>
              <div class="col-auto">
                <input type="text" id="email" name="email" class="form-control" value="{{ $user->credits }}" />
                @error('email')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="email" class="col-form-label">Kilépési kód</label>
              </div>
              <div class="col-auto">
                <input type="text" id="email" name="email" class="form-control" value="{{ $user->exit_code }}" />
                @error('email')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Szerkesztés</button>
        </form>
      </div>
    </div>
  @endsection
