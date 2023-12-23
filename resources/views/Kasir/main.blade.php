@extends('Tamplate.lte')

@section('content')
    @include('kasir.antrian')
    @include('kasir.input')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page select2 plugins -->
    <script src="{{ asset('vendor/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/select2/js/select2.full.min.js') }}"></script>

    {{-- --SweetAlert2-- --}}
    <script src=" {{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- my script -->
    <script src="{{ asset('js/tamplate.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/antrianKasir.js') }}"></script>
    <script src="{{ asset('js/mainKasir.js') }}"></script>
@endsection
