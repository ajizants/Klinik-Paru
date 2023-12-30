{{-- @extends('layouts.layout') --}}
@extends('tamplate.lte')

@section('content')
    <canvas id="confetti"></canvas>

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
    <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
@endsection
