@extends('layouts.admin')
@section('title', 'Szekrények listája')

@section('content')
  @if (Session::has('create'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen létrehoztad az öltözőszekrényt! (Szám: <strong>{{ Session::get('create') }}</strong>)
    </div>
    </p>
  @elseif (Session::has('edit'))
    <p>
    <div class="alert alert-success" role="alert">
      Sikeresen szerkesztetted az öltözőszekrényt! (Szám: <strong>{{ Session::get('edit') }}</strong>)
    </div>
    </p>
  @elseif (Session::has('delete'))
    <p>
    <div class="alert alert-danger" role="alert">
      Sikeresen törölted az öltözőszekrényt! (Szám: <strong>{{ Session::get('delete') }}</strong>)
    </div>
    </p>
  @endif

  <h2 class="mb-3">Szekrények listája</h2>
  <div class="card">
    <div class="card-body">
      <div class="card-text">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Edzőterem</th>
                <th>Szám</th>
                <th>Nem</th>
                <th>Hozzátartozó vendég</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($lockers as $locker)
                <tr>
                  <td>{{ $locker->id }}</td>
                  <td>{{ $locker->gym->name }}</td>
                  <td>{{ $locker->number }}</td>
                  <td>{{ $locker->gender == 'male' ? 'férfi' : 'nő' }}</td>
                  <td>{{ $locker->is_used() ? $locker->get_user->name : '-' }}</td>
                  <td>
                    @if (!$locker->is_used())
                      <a href="{{ route('lockers.edit', $locker->id) }}" class="link-primary">
                        <x-ri-edit-fill class="icon" style="height: 22px" />
                      </a>
                      <a href="{{ route('lockers.delete', $locker->id) }}" class="link-primary">
                        <x-ri-delete-back-2-fill class="icon" style="height: 22px" />
                      </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="d-flex justify-content-center">
            {{ $lockers->links() }}
          </div>
        </div>
      </div>

      @if ($button_show)
        <a href="{{ route('lockers.create') }}" class="btn btn-primary">Új szekrény</a>
      @else
        <div class="alert alert-warning" role="alert">
          Új öltözőszekrény létrehozásához hozz létre edzőtermet!
        </div>
      @endif
    </div>
  </div>
@endsection
