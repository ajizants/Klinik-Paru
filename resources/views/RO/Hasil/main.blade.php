@extends('Template.lte')

@section('content')
    @include('RO.Hasil.input')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    {{-- <script src="{{ asset('js/mainRo.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/populate.js') }}"></script> --}}
    {{-- <script type="text/javascript"> --}}
    {{-- var appUrlRo = @json($appUrlRo); --}}
    {{-- </script> --}}
@endsection
