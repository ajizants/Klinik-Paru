{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('RO.LogBook.input')


    @include('Template.script')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/logBookRO.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
@endsection
