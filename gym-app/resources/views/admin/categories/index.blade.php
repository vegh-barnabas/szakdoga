@extends('layouts.admin')
@section('title', 'Felhasználók listája')

@section('content')
  @if (Session::has('create'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen létrehoztad a(z) <strong>{{ Session::get('create') }}</strong> kategóriát!
    </div>
    </p>
  @endif
  @if (Session::has('edit'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted a(z) <strong>{{ Session::get('edit') }}</strong> kategóriát!
    </div>
    </p>
  @endif
  @if (Session::has('delete'))
    <p>
    <div class="alert alert-danger" role="alert">
      Sikeresen törölted a(z) <strong>{{ Session::get('delete') }}</strong> kategóriát!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Kategóriák listája</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <table class="table">
          <thead>
            <tr>
              <th>Név</th>
              <th>Stílus</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($categories as $category)
              <tr>
                <td>{{ $category->name }}</td>
                <td><span class="badge rounded-pill bg-{{ $category->style }}">{{ $category->style }}</span></td>
                <td>
                  <a href="{{ route('categories.edit', $category->id) }}" class="link-primary">
                    <x-ri-edit-fill class="icon" style="height: 22px" />
                  </a>
                  <a href="{{ route('categories.delete', $category->id) }}" class="link-primary">
                    <x-ri-delete-back-2-fill class="icon" style="height: 22px" />
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <a href="{{ route('categories.create') }}" class="btn btn-primary">Új kategória</a>
    </div>
  </div>
@endsection
