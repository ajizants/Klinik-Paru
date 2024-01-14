{{-- @extends('layouts.layout') --}}
@extends('Template.lte')
@section('content')
    @include('IGD.GudangIGD.inventaris')

    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>
    @include('Template.script')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/gudangIGD.js') }}"></script>
@endsection
