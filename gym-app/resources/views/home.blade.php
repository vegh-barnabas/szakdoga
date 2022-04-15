@extends('layouts.app')
@section('title', 'Edzőtermek')

@section('content')
<h1 class="mb-4">Válassz edzőtermet</h1>
    <div class="row justify-content-center">
        @foreach ($gyms as gym)
        <div class="col">
            <div class="card">
                <img src="img/gmaps.png" class="card-img-top" alt="" />
                <div class="card-body">
                    <h5 class="card-title mb-2">Valami utca edzőterem</h5>
                    <div class="categories mb-2">
                        <span class="badge rounded-pill bg-primary">0-24</span>
                        <span class="badge rounded-pill bg-secondary">Súlyok</span>
                        <span class="badge rounded-pill bg-success">Szauna</span>
                    </div>
                    <div class="mb-4">
                        <p class="card-text">0-24 edzőterem megfelelő felszereltséggel.</p>
                    </div>
                    <button class="btn btn-success">Tovább</a>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col">
            <div class="card">
                <img src="img/gmaps.png" class="card-img-top" alt="" />
                        <div class="card-body">
                            <h5 class="card-title mb-2">Semmi utca edzőterem</h5>
                            <div class="categories mb-2">
                                <span class="badge rounded-pill bg-primary">0-24</span>
                                <span class="badge rounded-pill bg-secondary">Súlyok</span>
                                <span class="badge rounded-pill bg-success">Szauna</span>
                            </div>
                            <div class="mb-4">
                                <p class="card-text">0-24 edzőterem megfelelő felszereltséggel.</p>

                            </div>
                            <button class="btn btn-success">Tovább</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="img/gmaps.png" class="card-img-top" alt="" />
                        <div class="card-body">
                            <h5 class="card-title mb-2">Harap utca edzőterem</h5>
                            <div class="categories mb-2">
                                <span class="badge rounded-pill bg-primary">0-24</span>
                                <span class="badge rounded-pill bg-secondary">Súlyok</span>
                                <span class="badge rounded-pill bg-success">Szauna</span>
                            </div>
                            <div class="mb-4">
                                <p class="card-text">0-24 edzőterem megfelelő felszereltséggel.</p>

                            </div>
                            <button class="btn btn-success">Tovább</a>
                        </div>
                    </div>
                </div>
            </div>
@endsection
