@extends('layouts.admin')
@section('title', 'Felhasználó szerkesztése')

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

  <h2 class="mb-3">Felhasználó szerkesztése</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $user->name }} (ID: {{ $user->id }})</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="name" class="col-form-label">Név</label>
              </div>
              <div class="col-auto">
                <input type="text" id="name" name="name" class="form-control"
                  value="{{ old('name') ?? $user->name }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="email" class="col-form-label">E-mail cím</label>
              </div>
              <div class="col-auto">
                <input type="text" id="email" name="email" class="form-control"
                  value="{{ old('email') ?? $user->email }}" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="gender" class="col-form-label">Nem</label>
              </div>
              <div class="col-auto">
                <select id="gender" name="gender" class="form-select">
                  <option value="male"
                    {{ (old('gender') == 'male' ? 'checked' : $user->gender == 'male' && !old('gender')) ? 'selected' : '' }}>
                    férfi
                  </option>
                  <option value="female"
                    {{ (old('gender') == 'female' ? 'checked' : $user->gender == 'female' && !old('gender')) ? 'selected' : '' }}>
                    nő
                  </option>
                </select>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="permission" class="col-form-label">Jogosultság</label>
              </div>
              <div class="col-auto">
                <select id="permission" name="permission" class="form-select">
                  <option value="user"
                    {{ (old('permission') == 'user' ? 'selected' : !$user->is_receptionist() && !old('permission')) ? 'selected' : '' }}>
                    vendég</option>
                  <option value="receptionist"
                    {{ (old('permission') == 'receptionist' ? 'selected' : $user->is_receptionist() && !old('permission')) ? 'selected' : '' }}>
                    recepciós
                  </option>
                </select>
              </div>
            </div>
          </div>
          @if ($user->is_receptionist())
            <div class="mb-3">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="prefered_gym" class="col-form-label">Recepcióshoz tartozó edzőterem</label>
                </div>
                <div class="col-auto">
                  <select id="prefered_gym" name="prefered_gym" class="form-select">
                    @foreach ($gyms as $gym)
                      <option value="{{ $gym->id }}"
                        {{ old('prefered_gym') ?? $user->prefered_gym == $gym->id && !old('prefered-gym') ? 'selected' : '' }}>
                        {{ $gym->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          @endif
          @if (!$user->is_receptionist())
            <div class="mb-3">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="credits" class="col-form-label">Kreditek</label>
                </div>
                <div class="col-auto">
                  <input type="text" id="credits" name="credits" class="form-control"
                    value="{{ old('credits') ?? $user->credits }}" />
                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="exit_code" class="col-form-label">Kilépési kód</label>
                </div>
                <div class="col-auto">
                  <input type="text" id="exit_code" name="exit_code" class="form-control"
                    value="{{ old('exit_code') ?? $user->exit_code }}" />
                </div>
              </div>
            </div>
          @endif
      </div>

      <button type="submit" class="btn btn-success">Szerkesztés</button>
      </form>
    </div>
  </div>
@endsection
