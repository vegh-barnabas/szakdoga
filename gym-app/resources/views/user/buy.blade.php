@extends('layouts.user')
@section('title', 'Főoldal')

@section('content')
<h2>Jegy/bérlet vásárlás</h2>
            <p>Ezen az oldalon tudsz a kreditedből jegyeket és bérleteket vásárolni. Vigyázz, melyiket választod ki, mert a vásárlás után nem tudod már visszamondani a terméket.</p>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Harap utcai edzőterem</h5>
                    <h6 class="card-subtitle text-muted">elérhető jegyek/bérletek</h6>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <h5 class="mb-3">Elérhető kreditek: <b>6500</b></h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Név</th>
                                    <th>Megjegyzés</th>
                                    <th>Elérhető</th>
                                    <th>Ár</th>
                                    <th>Vásárlás/hosszabbítás</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Diák napijegy</td>
                                    <td>Belépéskor diákigazolvány felmutatása szükséges.</td>
                                    <td>Végtelen</td>
                                    <td>900 kredit</td>
                                    <td>
                                        <button class="btn btn-success">Vásárlás</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Felnőtt bérlet</td>
                                    <td></td>
                                    <td>Végtelen</td>
                                    <td>7500 kredit</td>
                                    <td>
                                        <button class="btn btn-primary">Hosszabbítás</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Szaunabérlet</td>
                                    <td>Első 3 ingyenes!</td>
                                    <td>3 darab</td>
                                    <td>ingyenes</td>
                                    <td>
                                        <button class="btn btn-success">Vásárlás</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Szaunabérlet</td>
                                    <td></td>
                                    <td>végtelen</td>
                                    <td>4000 kredit</td>
                                    <td>
                                        <button class="btn btn-success">Vásárlás</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-link">Edzőterem váltása</button>
                    </div>
                </div>
            </div>
@endsection
