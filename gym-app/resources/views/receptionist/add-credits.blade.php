@extends('layouts.receptionist')
@section('title', 'Kreditek feltöltése')

@section('content')
  @if (Session::has('success'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen feltöltöttél <strong>{{ Session::get('success.amount') }}</strong> összeget
      <strong>{{ Session::get('success.name') }}</strong> vendégnek!
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

  <h2 class="mb-3">Kreditek feltöltése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym->name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('receptionist.add-credits') }}" method="POST">
          @csrf
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Vendég neve</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="amount" class="col-form-label">Feltöltendő kredit</label>
              </div>
              <div class="col-auto">
                <input type="text" min="1" id="amount" name="amount" class="form-control"
                  value="{{ old('amount') }}" />
              </div>
            </div>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="money_recieved" name="money_recieved" />
            <label class="form-check-label" for="money_recieved">
              <b class="text-danger">
                Az összeget megkaptam
              </b>
            </label>
          </div>

          <button type="submit" class="btn btn-success">Tovább</button>
        </form>
      </div>
    </div>
  @endsection
