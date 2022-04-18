@extends('layouts.user')
@section('title', 'Főoldal')

@section('content')
<h2>Statisztika</h2>
<p>Ezen az oldalon tudsz az edzéseidről és az edzőteremről statisztikákat nézni.</p>
<div class="row">
<div class="col-6">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Harap utcai edzőterem</h5>
            <h6 class="card-subtitle text-muted">Statisztika - látogatásaid</h6>
        </div>
        <div class="card-body">
            <div class="card-text">
                <h5>Látogatásaid</h5>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Dátum</th>
                            <th>Mettől</th>
                            <th>Meddig</th>
                            <th>Időtartam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2022. 02. 24.</td>
                            <td>14:00</td>
                            <td>16:12</td>
                            <td>2 óra 12 perc</td>
                        </tr>
                        <tr>
                            <td>2022. 02. 27.</td>
                            <td>10:06</td>
                            <td>11:04</td>
                            <td>58 perc</td>
                        </tr>
                        <tr>
                            <td>2022. 02. 28.</td>
                            <td>17:54</td>
                            <td>18:55</td>
                            <td>1 óra 1 perc</td>
                        </tr>
                    </tbody>
                </table>

                <h6>Átlagban 1 óra 24 percet tartózkodsz az edzőteremben.</h6>
            </div>
        </div>
    </div>
</div>
<div class="col-6">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Harap utcai edzőterem</h5>
            <h6 class="card-subtitle text-muted">Statisztika - edzőterem mai látogatottsága</h6>
        </div>
        <div class="card-body">
            <div class="card-text">
                <h5>Edzőterem mai látogatottsága</h5>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Időpont</th>
                            <th>Bent tartózkodók száma</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10:00</td>
                            <td>27</td>
                        </tr>
                        <tr>
                            <td>11:00</td>
                            <td>25</td>
                        </tr>
                        <tr>
                            <td>12:00</td>
                            <td>11</td>
                        </tr>
                        <tr>
                            <td>13:00</td>
                            <td>30</td>
                        </tr>
                        <tr></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
