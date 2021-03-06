@extends('layouts.admin')
@section('title', 'Felhasználók listája')

@section('content')
  @if (Session::has('edit'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted <strong>{{ Session::get('edit') }}</strong> felhasználót!
    </div>
    </p>
  @endif

  @if (Session::has('no-gym-error'))
    <p>
    <div class="alert alert-danger" role="alert">
      Nem tudsz felhasználót szerkeszteni úgy, hogy nincs 1 edzőterem sem!
    </div>
    </p>
  @endif

  <h2 class="mb-3">Felhasználók listája</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <div class="table-responsive">
          <table class="table table-hover">
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
                  @if (!$user->is_admin())
                    <td>
                      <a href="{{ route('users.edit', $user->id) }}" class="link-primary">{{ $user->name }}</a>
                    </td>
                  @else
                    <td>{{ $user->name }}</td>
                  @endif
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->get_user_type() }}</td>
                  <td>{{ $user->get_prefered_gym_name() }}</td>
                  <td>{{ $user->get_user_type() == 'Vendég' ? $user->credits : '' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="d-flex justify-content-center">
            {{ $all_users->links() }}
          </div>
        </div>
      </div>
    </div>
  @endsection
