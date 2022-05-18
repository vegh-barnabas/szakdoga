@extends('layouts.admin')
@section('title', 'Felhasználók listája')

@section('content')
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
                  <a href="{{ route('edit-gym', $gym->id) }}" class="link-primary">✏</a>
                  <a href="#" class="link-primary">❌</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <a href="{{ route('add-gym') }}" class="btn btn-primary">Új edzőterem</a>
    </div>
  </div>
@endsection
