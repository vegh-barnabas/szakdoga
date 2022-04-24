@extends('layouts.receptionist')
@section('title', 'Beléptetés')

@section('content')
    <h2 class="mb-3">Vendég beléptetése</h2>
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
                value="{{ $user->name }}"
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
                value="{{ $user->gender === 0 ? "férfi" : "nő" }}"
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
                value="{{ $ticket->type->name }}"
                disabled
                />
            </div>
            </div>
            <h2 class="mb-3">Beállítandó adatok</h2>
            <div class="mb-3">
            <label for="locker" class="form-label"
                >Válassz szekrényt! (férfi öltöző)</label
            >
            <select class="form-select" id="locker" name="locker">
                <option value="002" selected="selected">002</option>
                <option value="006">006</option>
                <option value="010">010</option>
            </select>
            </div>
            <div class="mb-3 form-check">
            <input
                type="checkbox"
                class="form-check-input"
                id="exampleCheck1"
            />
            <label class="form-check-label" for="exampleCheck1"
                ><b class="text-danger"
                >a kulcsot odaadtam a megfelelő szekrényhez</b
                ></label
            >
            </div>

            <button href="home.html" type="submit" class="btn btn-success">
            Vendég beléptetése
            </button>
        </form>
        </div>
    </div>
@endsection

