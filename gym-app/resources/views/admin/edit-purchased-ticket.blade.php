@extends('layouts.admin')
@section('title', 'Vásárolt jegy szerkesztése')

@section('content')
  @if (Session::has('success'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted <strong>{{ Session::get('success') }}</strong> jegyet!
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
                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" />
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
                <label for="gender" class="col-form-label">Nem</label>
              </div>
              <div class="col-auto">
                <select id="gender" class="form-select">
                  <option value="0" {{ $user->gender == 0 ? 'selected' : '' }}>férfi</option>
                  <option value="1" {{ $user->gender == 1 ? 'selected' : '' }}>nő</option>
                </select>
                @error('gender')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="permission" class="col-form-label">Jogosultság</label>
              </div>
              <div class="col-auto">
                <select id="permission" class="form-select">
                  <option value="guest" {{ !$user->is_receptionist ? 'selected' : '' }}>vendég</option>
                  <option value="receptionist" {{ $user->is_receptionist ? 'selected' : '' }}>recepciós</option>
                </select>
                @error('permission')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="credits" class="col-form-label">Kreditek</label>
              </div>
              <div class="col-auto">
                <input type="text" id="credits" name="credits" class="form-control" value="{{ $user->credits }}" />
                @error('credits')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            @if (!$user->is_receptionist)
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="exitcode" class="col-form-label">Kilépési kód</label>
                </div>
                <div class="col-auto">
                  <input type="text" id="exitcode" name="exitcode" class="form-control"
                    value="{{ $user->exit_code }}" />
                  @error('exitcode')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
            @endif
          </div>

          <hr>

          <div class="row g-3 align-items-center">
            <div class="col-2">
              <label for="newpw" class="col-form-label">Új jelszó</label>
            </div>
            <div class="col-auto">
              <input type="text" id="newpw" name="newpw" class="form-control" />
              @error('newpw')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="row g-3 align-items-center">
            <div class="col-2">
              <label for="newpw2" class="col-form-label">Új jelszó mégegyszer</label>
            </div>
            <div class="col-auto">
              <input type="text" id="newpw2" name="newpw2" class="form-control" />
              @error('newpw2')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>

          <button type="submit" class="btn btn-success">Szerkesztés</button>
        </form>
      </div>
    </div>
  @endsection
