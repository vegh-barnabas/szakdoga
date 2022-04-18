@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Erősítsd meg az email címedet') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Egy friss megerősítő email lett küldve az email címedre.') }}
                        </div>
                    @endif

                    {{ __('Kérlek nézd meg az emailjeidet.') }}
                    {{ __('Ha nem kaptál emailt, akkor ') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('kattints ide egy új megerősítő email kéréséhez') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
