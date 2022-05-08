@extends('layouts.user')
@section('title', 'Beállítások')


@section('content')
  <h2>Beállítások</h2>
  <p>Ezen az oldalon tudod a beállításaidat módosítani.</p>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><b>user1</b> beállításai</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form>
          <div class="mb-3">
            <label for="theme" class="form-label">Oldal témája</label>
            <select class="form-select" id="theme" name="theme">
              <option value="light" selected="selected">Világos</option>
              <option value="dark">Sötét</option>
              <option value="drawn">Rajzolt</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="theme" class="form-label">Kiválasztott edzőterem</label>
            <select class="form-select" id="theme" name="theme">
              @foreach ($gyms as $gym)
                <option value={{ $gym->id }} {{ $current_gym == $gym->id ? 'selected' : '' }}>
                  {{ $gym->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="theme" class="form-label">Alapértelmezett edzőterem</label>
            <select class="form-select" id="theme" name="theme">
              <option value="none" {{ $selected_gym_id == null ? 'selected' : '' }}>Nincs</option>
              @foreach ($gyms as $gym)
                <option value={{ $gym->id }} {{ $selected_gym_id == $gym->id ? 'selected' : '' }}>
                  {{ $gym->name }}
                </option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Beállítások alkalmazása</button>
        </form>
      </div>
    </div>
  </div>
  <div class="card mt-2 mb-3">
    <div class="card-header">
      <h5 class="card-title">további beállítások</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="emailNew" class="col-form-label">Új e-mail cím</label>
              </div>
              <div class="col-auto">
                <input type="email" id="emailNew" class="form-control" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="pwNew" class="col-form-label">Új jelszó</label>
              </div>
              <div class="col-auto">
                <input type="password" id="pwNew" class="form-control" aria-describedby="passwordHelpInline" />
              </div>
              <div class="col-auto">
                <span id="passwordHelpInline" class="form-text">A jelszó 8 és 20 karakter között legyen.</span>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="pwNew" class="col-form-label">Új jelszó mégegyszer</label>
              </div>
              <div class="col-auto">
                <input type="password" id="pwNew" class="form-control" />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="pwNew" class="col-form-label">Régi jelszó</label>
              </div>
              <div class="col-auto">
                <input type="password" id="pwNew" class="form-control" />
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-danger">Beállítások alkalmazása</button>
        </form>
      </div>
    </div>
  </div>
@endsection
