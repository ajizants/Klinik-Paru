@extends('Template.lte')

@section('content')
    <canvas id="confetti"></canvas>

    @include('Pendaftaran.input')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/pendaftaran.js') }}"></script>
@endsection
