{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('DotsCenter.Master.input')




    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>
    @include('Template.script')

    <!-- my script -->
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/MasterDots.js') }}"></script>
@endsection
