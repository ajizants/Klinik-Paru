@extends('Template.lte')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid" style="height: 70vh">
        <div class="text-center mt-5">
            <h1 class="display-4">403 Forbidden</h1>
            <p class="lead">Anda tidak memiliki hak mengakses halaman ini!</p>
            <div class="form-row d-flex justify-content-center">
                <a href="{{ url()->previous() }}" class="btn btn-warning col-1 mx-2">Kembali</a>
                <a href="{{ route('home') }}" class="btn btn-primary col-1 mx-2">Dashboard</a>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-5">
            <img src="{{ asset('img/Cry.png') }}" alt="Forbidden" style="height: 200px;width: 200px">
        </div>
    </div>


    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
@endsection
