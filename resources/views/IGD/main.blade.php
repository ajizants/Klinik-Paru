{{-- @extends('layouts.layout') --}}
@extends('tamplate.lte')

@section('content')
    @include('igd.antrian')
    @include('igd.igd')


    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Tamplate.footer')

    </div>
    @include('Tamplate.script')


    <!-- my script -->
    <script src="{{ asset('js/tamplate.js') }}"></script>
    <script src="{{ asset('js/antrianIGD.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainIGD.js') }}"></script>
@endsection
