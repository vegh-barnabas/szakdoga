@extends('layouts.user')
@section('title', 'Szenzitív beállítások')

@section('content')
  @if (Session::has('success'))
    <p>
    <div class="alert alert-danger" role="alert">
      Sikeresen szerkesztetted a beállításaid!
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

  <h2>Szenzitív beállítások</h2>
  <p>Ezen az oldalon tudod a beállításaidat módosítani.</p>
  <div class="card mt-2 mb-3">
    <div class="card-header">
      <h5 class="card-title">Beállítások</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action={{ route('sensitive-settings') }} method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="email" class="col-form-label">Új e-mail cím</label>
              </div>
              <div class="col-auto">
                <input type="email" id="email" name="email" class="form-control" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="password" class="col-form-label">Új jelszó</label>
              </div>
              <div class="col-auto">
                <input type="password" id="password" class="form-control" name="password"
                  aria-describedby="passwordHelpInline" />
              </div>
              <div class="col-auto">
                <span id="passwordHelpInline" class="form-text">A jelszó 8 és 20 karakter között legyen.</span>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="password_confirmation" class="col-form-label">Új jelszó újra</label>
              </div>
              <div class="col-auto">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="current_password" class="col-form-label">Régi jelszó</label>
              </div>
              <div class="col-auto">
                <input type="password" id="current_password" name="current_password" class="form-control" />
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-danger">Beállítások alkalmazása</button>
        </form>
      </div>
    </div>
  </div>
@endsection
