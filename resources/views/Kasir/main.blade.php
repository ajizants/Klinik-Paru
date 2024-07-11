@extends('Template.lte')

@section('content')
    @include('Kasir.antrian')
    @include('Kasir.input')

    /div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>
    @include('DotsCenter.Trans.modals')
    @include('Template.script')

    <!-- my script -->

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/antrianKasir.js') }}"></script>
    <script src="{{ asset('js/mainKasir.js') }}"></script>
@endsection
