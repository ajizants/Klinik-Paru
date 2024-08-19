{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('RO.Master.input')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/masterRo.js') }}"></script>
@endsection
