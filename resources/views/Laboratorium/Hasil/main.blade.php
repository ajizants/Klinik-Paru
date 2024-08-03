{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('Laboratorium.Hasil.antrian')
    @include('Laboratorium.Hasil.input')



    @include('Template.script')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainLabHasil.js') }}"></script>
@endsection
