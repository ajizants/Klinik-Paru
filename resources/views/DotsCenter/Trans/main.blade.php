{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('DotsCenter.Trans.antrian')
    @include('DotsCenter.Trans.input')




    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>
    @include('DotsCenter.Trans.modals')
    @include('Template.script')

    <!-- my script -->

    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/antrianDots.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/mainDots.js') }}"></script>
@endsection
