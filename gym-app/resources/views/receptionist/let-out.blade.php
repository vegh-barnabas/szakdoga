@extends('layouts.receptionist')
@section('title', 'Kiléptetés')

@section('content')
  @if (Session::has('error'))
    <p>
    <div class="alert alert-danger" role="alert">
      A(z) <strong>{{ Session::get('error.code') }}</strong> kilépési kóddal rendelkező felhasználó
      (<strong>{{ Session::get('error.user') }}</strong>) nincs belépve!
    </div>
    </p>
  @elseif (Session::has('not-found'))
    <p>
    <div class="alert alert-danger" role="alert">
      Nem létezik <strong>{{ Session::get('not-found') }}</strong> kilépési kóddal rendelkező felhasználó!
    </div>
    </p>
  @elseif (Session::has('success'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen kiengedted <strong>{{ Session::get('success') }}</strong> vendéget!
    </div>
    </p>
  @elseif (Session::has('not-this-gym'))
    <p>
    <div class="alert alert-danger" role="alert">
      A vendég által felhasznált <strong>{{ Session::get('not-this-gym.code') }}</strong> kódú jegy nem ehhez az
      edzőteremhez tartozik!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Vendég kiléptetése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <h2 class="mb-3">Add meg a vendég kilépési kódját!</h2>
        <form action="{{ route('receptionist.let-out.index') }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="exit_code" class="col-form-label">Vendég kilépési kódja</label>
              </div>
              <div class="col-auto">
                <input type="text" id="exit_code" name="exit_code" class="form-control" />
                @error('exit_code')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Tovább</button>
        </form>
      </div>
    </div>
  @endsection
