{{-- @extends('layouts.layout') --}}
@extends('Template.lte')
@section('content')
    @include('IGD.GudangIGD.inventaris')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/gudangIGD.js') }}"></script>
@endsection
