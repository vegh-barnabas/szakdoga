@extends('layouts.receptionist')
@section('title', 'Főoldal')

@section('content')
  <h2>Üdv újra, <b>receptionist1</b>!</h2>
  <div class="row">
    <div class="col p-4">
      <div class="row">
        <div class="col">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Legutóbb megvásárolt bérletek</h5>
                <h6 class="card-subtitle text-muted mb-2">
                  Harap utcai edzőterem
                </h6>
                <p class="card-text">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Tulajdonos</th>
                      <th scope="col">Név</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><b>user1</b></td>
                      <td>Szaunabérlet</td>
                      <td>2022.04.14</td>
                      <td class="text-success">Érvényes</td>
                    </tr>
                    <tr>
                      <td><b>user2</b></td>
                      <td>Bérlet</td>
                      <td>2022.01.14</td>
                      <td class="text-danger">Lejárt</td>
                    </tr>
                  </tbody>
                </table>
                </p>
                <a href="#" class="card-link">többi bérlet megjelenítése</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <div class="row">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Legutóbb megvásárolt jegyek</h5>
                <h6 class="card-subtitle text-muted mb-2">
                  Harap utcai edzőterem
                </h6>
                <p class="card-text">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Tulajdonos</th>
                      <th scope="col">Név</th>
                      <th scope="col">Lejárat</th>
                      <th scope="col">Státusz</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tickets as $ticket)
                      <tr>
                        <td><b>{{ $ticket->user->name }}</b></td>
                        <td>{{ $ticket->type->name }}</td>
                        <td>{{ $ticket->expiration }}</td>
                        @if ($ticket->used())
                          <td class="text-warning">Lejárt</td>
                        @elseif ($ticket->expiration < date('Y-m-d H:i:s'))
                          <td class="text-danger">Lejárt</td>
                        @else
                          <td class="text-success">Érvényes</td>
                        @endif
                      </tr>
                    @endforeach
                    <tr>
                      <td><b>user2</b></td>
                      <td>Diákjegy</td>
                      <td>2022.04.14</td>
                      <td class="text-success">Érvényes</td>
                    </tr>
                    <tr>
                      <td><b>user2</b></td>
                      <td>Diákjegy</td>
                      <td>2022.01.14</td>
                      <td class="text-danger">Lejárt</td>
                    </tr>
                    <tr>
                      <td><b>user1</b></td>
                      <td>Diákjegy</td>
                      <td>2022.05.20</td>
                      <td class="text-warning">Felhasznált</td>
                    </tr>
                  </tbody>
                </table>
                </p>
                <a href="#" class="card-link">többi jegy megjelenítése</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col align-self-center p-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Státusz</h5>
          <h6 class="card-subtitle text-muted mb-2">
            Harap utcai edzőterem
          </h6>
          <p class="card-text">
            <!-- ikon -->
          <h1>Belépett vendégek: <b class="text-success">{{ $enterances->count() }}</b></h1>
          <p class="card-text">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Vendég neve</th>
                <th scope="col">Felhasznált bérlet/jegy</th>
                <th scope="col">Belépés időpontja</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($enterances as $enterance)
                <tr>
                  <td><b>{{ $enterance->user->name }}</b></td>
                  <td>{{ $enterance->ticket->type->name }}</td>
                  <td>{{ $enterance->enter }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          </p>
          <a href="#" class="card-link">többi belépett vendég megjelenítése</a>
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Modal body text goes here.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection
