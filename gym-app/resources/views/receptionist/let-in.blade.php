@extends('layouts.receptionist')
@section('title', 'Beléptetés')

@section('content')
  @if (Session::has('error'))
    <p>
    <div class="alert alert-danger" role="alert">
      A(z) <strong>{{ Session::get('error.code') }}</strong> kódú jeggyel rendelkező felhasználó
      (<strong>{{ Session::get('error.user') }}</strong>) már be van lépve!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Vendég beléptetése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Harap utcai edzőterem</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <h2 class="mb-3">Add meg a vendég belépési kódját!</h2>
        <form action="{{ route('let-in') }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="enterance_code" class="col-form-label">Vendég belépési kódja</label>
              </div>
              <div class="col-auto">
                <input type="text" id="enterance_code" name="enterance_code" class="form-control" />
                @error('enterance_code')
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
