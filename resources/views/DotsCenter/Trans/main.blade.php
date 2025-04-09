@extends('Template.lte')

@section('content')
    @include('DotsCenter.Trans.antrian')
    @include('DotsCenter.Trans.input')

    @include('DotsCenter.Trans.modals')

    <!-- my script -->

    <script type="text/javascript">
        let tb = @json($pasienTB)
    </script>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/antrianDots.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/mainDots.js') }}"></script>
@endsection
