@extends('layouts.user')
@section('title', 'Beállítások')

@section('content')
  @if (Session::has('success'))
    <p>
    <div class="alert alert-success" role="alert">
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

  <h2>Beállítások</h2>
  <p>Ezen az oldalon tudod a beállításaidat módosítani.</p>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title"><b>{{ Auth::user()->name }}</b> beállításai</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <form action="{{ route('guest.settings') }}" method="POST">
          @csrf
          @method('patch')
          <div class="mb-3">
            <label for="current" class="form-label">Kiválasztott edzőterem</label>
            <select class="form-select" id="current" name="current">
              @foreach ($gyms as $gym)
                <option value={{ $gym->id }}
                  {{ (old('current') == $gym->id ? 'selected' : $current_gym == $gym->id && !old('current')) ? 'selected' : '' }}>
                  {{ $gym->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="prefered" class="form-label">Alapértelmezett edzőterem</label>
            <select class="form-select" id="prefered" name="prefered">
              <option value="none" {{ Auth::user()->prefered_gym == null ? 'selected' : '' }}>Nincs</option>
              @foreach ($gyms as $gym)
                <option value={{ $gym->id }}
                  {{ (old('prefered') == $gym->id ? 'selected' : $gym->id == Auth::user()->prefered_gym && !old('current')) ? 'selected' : '' }}>
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
@endsection
