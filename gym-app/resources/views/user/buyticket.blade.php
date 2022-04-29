@extends('layouts.user')
@section('title', 'Beléptetés')

@section('content')
    <h2 class="mb-3">Jegy meghosszabbítása</h2>
    <div class="card">
    <div class="card-header">
        <h5 class="card-title">Harap utcai edzőterem</h5>
    </div>
    <div class="card-body">
        <div class="card-text">
            <div class="alert alert-warning text-center" role="alert">
                <h3 class="mb-3">A bérlet meghosszabbítása 1 hónapra vonatkozik!</h2>
            </div>
            <form action="{{ route('buyticket', $ticket) }}" method="POST">
                @csrf
                <h2 class="mb-3">Bérlet adatai</h2>
                <div class="row g-3 align-items-center mb-3">
                <div class="col-2">
                    <label for="ticketId" class="col-form-label"
                    >Bérlet azonosítója</label
                    >
                </div>
                <div class="col-auto">
                    <input
                    type="text"
                    id="ticketId"
                    name="ticketId"
                    class="form-control"
                    value="{{ $ticket->id }}"
                    disabled
                    />
                </div>
                </div>
                <div class="row g-3 align-items-center mb-3">
                <div class="col-2">
                    <label for="ticketName" class="col-form-label"
                    >Bérlet neve</label
                    >
                </div>
                <div class="col-auto">
                    <input
                    type="text"
                    id="ticketName"
                    name="ticketName"
                    class="form-control"
                    value="{{ $ticket->type->name }}"
                    disabled
                    />
                </div>
                </div>
                <div class="row g-3 align-items-center mb-3">
                <div class="col-2">
                    <label for="ticketDesc" class="col-form-label"
                    >Bérlet leírása</label
                    >
                </div>
                <div class="col-auto">
                    <input
                    type="text"
                    id="ticketDesc"
                    name="ticketDesc"
                    class="form-control"
                    value="{{ $ticket->type->description }}"
                    disabled
                    />
                </div>
                </div>
                <div class="row g-3 align-items-center mb-3">
                <div class="col-2">
                    <label for="ticketPrice" class="col-form-label"
                    ><b>Bérlet ára</b></label
                    >
                </div>
                <div class="col-auto">
                    <input
                    type="text"
                    id="ticketPrice"
                    name="ticketPrice"
                    class="form-control"
                    value="{{ $ticket->type->price }} kredit"
                    disabled
                    />
                </div>
                </div>
                <button type="submit" class="btn btn-success">
                Hosszabbítás
                </button>
            </form>
        </div>
    </div>
@endsection

