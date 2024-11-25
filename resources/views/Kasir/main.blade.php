@extends('Template.lte')

@section('content')
    @include('Kasir.antrian')
    @include('Kasir.input')



    {{-- @include('Template.comingSoon') --}}

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/antrianKasir.js') }}"></script>
    <script src="{{ asset('js/mainKasir.js') }}"></script>
    <script>
        const itemPemeriksaan = @json($layanan);
    </script>
@endsection
