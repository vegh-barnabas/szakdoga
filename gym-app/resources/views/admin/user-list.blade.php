@extends('layouts.admin')
@section('title', 'Felhasználók listája')

@section('content')
  <h2 class="mb-3">Felhasználók listája</h2>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $gym_name }}</h5>
    </div>
    <div class="card-body">
      <div class="card-text">
        <table class="table">
          <thead>
            <tr>
              <th>Név</th>
              <th>ID</th>
              <th>Jogosultság</th>
              <th>Edzőterem</th>
              <th>Kreditek</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($all_users as $user)
              <tr>
                <form>
                  <td><a href="{{ route('edit-user', $user->id) }}" class="link-primary">{{ $user->name }}</a></td>
                </form>
                <td>{{ $user->id }}</td>
                <td>{{ $user->getUserType() }}</td>
                <td>{{ $user->getPreferedGymName() }}</td>
                <td>{{ $user->getUserType() == 'Vendég' ? $user->credits : '' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endsection
