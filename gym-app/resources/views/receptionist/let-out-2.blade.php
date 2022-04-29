@extends('layouts.receptionist')
@section('title', 'Beléptetés')

@section('content')
<h2 class="mb-3">Vendég kiléptetése</h2>
    <div class="card">
    <div class="card-header">
        <h5 class="card-title">Harap utcai edzőterem</h5>
    </div>
    <div class="card-body">
        <div class="card-text">
        <form>
            <h2 class="mb-3">Vendég adatai</h2>
            <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
                <label for="enterance-code" class="col-form-label"
                >Felhasználó neve</label
                >
            </div>
            <div class="col-auto">
                <input
                type="text"
                id="enterance-code"
                class="form-control"
                value="user1"
                disabled
                />
            </div>
            </div>
            <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
                <label for="enteranceCode" class="col-form-label"
                >Felhasználó neme</label
                >
            </div>
            <div class="col-auto">
                <input
                type="text"
                id="enteranceCode"
                class="form-control"
                value="férfi"
                disabled
                />
            </div>
            </div>
            <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
                <label for="usedTicket" class="col-form-label"
                >Felhasználandó bérlet/jegy</label
                >
            </div>
            <div class="col-auto">
                <input
                type="text"
                id="usedTicket"
                class="form-control"
                value="Szaunabérlet"
                disabled
                />
            </div>
            </div>
            <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
                <label for="usedTicket" class="col-form-label"
                >Belépés időpontja</label
                >
            </div>
            <div class="col-auto">
                <input
                type="text"
                id="usedTicket"
                class="form-control"
                value="2022. 03. 31. 17:30"
                disabled
                />
            </div>
            </div>
            <div class="row g-3 align-items-center mb-3">
            <div class="col-2">
                <label for="usedTicket" class="col-form-label"
                >Szekrényszám</label
                >
            </div>
            <div class="col-auto">
                <input
                type="text"
                id="usedTicket"
                class="form-control"
                value="002 (férfi öltöző)"
                disabled
                />
            </div>
            </div>
            <div class="mb-3 form-check">
            <input
                type="checkbox"
                class="form-check-input"
                id="exampleCheck1"
            />
            <label class="form-check-label" for="exampleCheck1"
                ><b class="text-danger"
                >a kulcsot visszakaptam a megfelelő szekrényhez</b
                ></label
            >
            </div>

            <button href="home.html" type="submit" class="btn btn-danger">
            Vendég kiléptetése
            </button>
        </form>
        </div>
    </div>
    </div>
@endsection
