@extends('layouts.user')
@section('title', 'Főoldal')

@section('content')
<h2>Üdv újra, <b>{{ Auth::user()->name }}</b>!</h2>
            <div class="row">
                <div class="col p-4">
                    <div class="row">
                      <div class="col">
                        <div class="row">
                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title">Bérletek</h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                  Harap utcai edzőterem
                                </h6>
                                <p class="card-text">
                                  <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">Név</th>
                                        <th scope="col">Lejárat</th>
                                        <th scope="col">Státusz</th>
                                        <th scope="col">Lehetőség</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Szaunabérlet</td>
                                        <td>2022.04.14</td>
                                        <td class="text-success">Aktív</td>
                                        <td><button type="button" class="btn btn-light">Hosszabbítás</button></td>
                                      </tr>
                                      <tr>
                                        <td>Bérlet</td>
                                        <td>2022.01.14</td>
                                        <td class="text-danger">Lejárt</td>
                                        <td><button type="button" class="btn btn-light">Megújítás</button></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </p>
                                <a href="#" class="card-link">többi helyszínhez tartozó bérlet megtekintése</a>
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
                                <h5 class="card-title">Jegyek</h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                  Harap utcai edzőterem
                                </h6>
                                <p class="card-text">
                                  <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">Név</th>
                                        <th scope="col">Lejárat</th>
                                        <th scope="col">Státusz</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>Diákjegy</td>
                                        <td>2022.04.14</td>
                                        <td class="text-success">Aktív</td>
                                      </tr>
                                      <tr>
                                        <td>Diákjegy</td>
                                        <td>2022.01.14</td>
                                        <td class="text-danger">Lejárt</td>
                                      </tr>
                                      <tr>
                                        <td>Diákjegy</td>
                                        <td>2022.05.20</td>
                                        <td class="text-warning">Felhasznált</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </p>
                                <a href="#" class="card-link">többi helyszínhez tartozó jegy megtekintése</a>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col p-3 align-self-center">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Státusz</h5>
                      <h6 class="card-subtitle mb-2 text-muted">
                        Harap utcai edzőterem
                      </h6>
                      <p class="card-text">
                        <!-- ikon -->
                        <h1 class="text-success">Belépve</h1>
                        <h3>2022. 03. 12. 8:14 óta</h3>
                        <button type="button" class="btn btn-danger text-white mt-4">Kilépési kód</button>
                      </p>
                    </div>
                  </div>
                </div>
            </div>
            @endsection
