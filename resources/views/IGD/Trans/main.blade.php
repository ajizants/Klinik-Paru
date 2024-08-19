{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    <canvas id="confetti"></canvas>

    @include('IGD.Trans.antrian')
    @include('IGD.Trans.input')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/antrianIGD.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainIGD.js') }}"></script>
@endsection
