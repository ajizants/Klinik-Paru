{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Area Chart -->
        <div class="card shadow mb-4" height="500">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                <h6 class="m-0 font-weight-bold text-primary">Data Kunjungan Pasien IGD Tahun:</h6>
                <div class="col-md-1 ">
                    <select id="year-selector" class="form-control-sm ">
                        @php
                            $startYear = 2021; // Tahun mulai
                            $currentYear = date('Y'); // Tahun saat ini
                        @endphp
                        @for ($year = $currentYear; $year >= $startYear; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart" class="mb-3 pb-3"></canvas>
                </div>
            </div>
        </div>


    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>
    @include('Template.script')

    <!-- my script -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>

    <script src="{{ asset('js/chart.js') }}"></script>
    <script>
        $(document).ready(function() {
                    $("#masukIGD").on("click", function() {
                            panggilPasien(
                                "selamat bertugas teman teman, aja kelalen madang, lan aja kelalen gosip, haha haha wkwk wkwk"
                            );
                        }
                    });
    </script>


    </body>

    </html>
@endsection
