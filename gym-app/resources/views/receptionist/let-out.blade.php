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
        <h2 class="mb-3">Add meg a vendég kilépési kódját!</h2>
        <form>
            <div class="mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-2">
                <label for="enterance-code" class="col-form-label"
                    >Vendég kilépési kódja</label
                >
                </div>
                <div class="col-auto">
                <input
                    type="text"
                    id="enterance-code"
                    class="form-control"
                />
                </div>
            </div>
            </div>

            <button type="submit" class="btn btn-success">Tovább</button>
        </form>
        </div>
    </div>
@endsection
