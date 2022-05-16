@extends('layouts.admin')
@section('title', 'Felhasználók listája')

@section('content')
  <h2 class="mb-3">Kategóriák listája</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <table class="table">
          <thead>
            <tr>
              <th>Név</th>
              <th>Stílus</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($categories as $category)
              <tr>
                <td>{{ $category->name }}</td>
                <td><span class="badge rounded-pill bg-{{ $category->style }}">{{ $category->style }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <a href="{{ route('add-category') }}" class="btn btn-primary">Új kategória</a>
    </div>
  </div>
@endsection
