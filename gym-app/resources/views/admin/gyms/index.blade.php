@extends('layouts.admin')
@section('title', 'Felhasználók listája')

@section('content')
  @if (Session::has('create'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen törölted a(z) <strong>{{ Session::get('create') }}</strong> edzőtermet!
    </div>
    </p>
  @endif
  @if (Session::has('edit'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted <strong>{{ Session::get('edit') }}</strong> edzőtermet!
    </div>
    </p>
  @endif

  @if (Session::has('delete'))
    <p>
    <div class="alert alert-danger" role="alert">
      Sikeresen törölted <strong>{{ Session::get('delete') }}</strong> edzőtermet!
    </div>
    </p>
  @endif


  <h2 class="mb-3">
    Edzőtermek listája</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <table class="table">
          <thead>
            <tr>
              <th>Név</th>
              <th>Cím</th>
              <th>Leírás</th>
              <th>Kategóriák</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($gyms as $gym)
              <tr>
                <td>{{ $gym->name }}</td>
                <td>{{ $gym->address }}</td>
                <td>{{ $gym->description }}</td>
                <td>
                  @foreach ($gym->categories as $category)
                    <span class="badge rounded-pill bg-{{ $category->style }}">{{ $category->name }}</span>
                  @endforeach
                </td>
                <td>
                  <a href="{{ route('gyms.edit', $gym->id) }}" class="link-primary">
                    <x-ri-edit-fill class="icon" style="height: 22px" />
                  </a>
                  <a href="{{ route('gyms.delete', $gym->id) }}" class="link-primary">
                    <x-ri-delete-back-2-fill class="icon" style="height: 22px" />
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <a href="{{ route('gyms.create') }}" class="btn btn-primary">Új edzőterem</a>
    </div>
  </div>
@endsection
