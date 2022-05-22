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
  @elseif (Session::has('not-found'))
    <p>
    <div class="alert alert-danger" role="alert">
      Nem létezik <strong>{{ Session::get('not-found.code') }}</strong> kódú jegy!
    </div>
    </p>
  @elseif (Session::has('success'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen beengedted <strong>{{ Session::get('success') }}</strong> vendéget!
    </div>
    </p>
  @elseif (Session::has('not-this-gym'))
    <p>
    <div class="alert alert-danger" role="alert">
      A(z) <strong>{{ Session::get('not-this-gym.code') }}</strong> kódú jegy nem ehhez az edzőteremhez tartotzik!
    </div>
    </p>
  @elseif (Session::has('used-ticket'))
    <p>
    <div class="alert alert-danger" role="alert">
      A(z) <strong>{{ Session::get('used-ticket.code') }}</strong> kódú jeggyet már felhasználták! Belépés dátuma:
      <strong>{{ Session::get('used-ticket.used') }}</strong>
    </div>
    </p>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <h2 class="mb-3">Vendég beléptetése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <h2 class="mb-3">Add meg a vendég belépési kódját!</h2>
        <form action="{{ route('receptionist.let-in.index') }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="enterance_code" class="col-form-label">Vendég belépési kódja</label>
              </div>
              <div class="col-auto">
                <input type="text" id="enterance_code" name="enterance_code" class="form-control" />
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Tovább</button>
        </form>
      </div>
    </div>
  @endsection
