@extends('Template.lte')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Area Chart -->
        <div class="card shadow mb-4 col">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start bg-teal">
                <h6 class="m-0 font-weight-bold">Data Kunjungan Pasien IGD Tahun:</h6>
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
            <div class="card-body mb-2">
                <div class="chart-area">
                    <canvas id="myAreaChart" class="mb-3 pb-3"></canvas>
                </div>
            </div>
        </div>
        {{-- Tabel rata rata dan terlama --}}
        <div class="card shadow">
            <div class="card-header bg-teal">
                <div class="row">
                    <h6 class="m-0 font-weight-bold">Rata-Rata Waktu Layanan, Tanggal :</h6>
                    {{-- <label class="col-form-label" for="tanggal">Rata-Rata Waktu Layanan, Tanggal :</label> --}}
                    <div class="form-group ml-2 mb-0 row">
                        <div class="input-group col-7">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" id="ratawaktulayanan">
                        </div>
                        <div class="input-group col-5">
                            <input type="date" class="form-control col-sm-9" id="tanggal" value="{{ old('date') }}"
                                required onchange="updtWaktuLayanan();">
                            <div class="input-group-addon btn btn-success">
                                <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                                    title="Update Pasien Hari ini" id="cariantrian" onclick="updtWaktuLayanan();"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Rata-Rata Layanan Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="chart-area">
                                <canvas id="myChart" class="mb-3 pb-3"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Rata-Rata Waktu Tunggu Layanan Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="rataTabel" width="100%" cellspacing="0">
                                    <thead class="bg bg-teal">
                                        <tr>
                                            <th>Label</th>
                                            <th>Waktu Tunggu (menit)</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table rows will be dynamically populated with data -->
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Waktu Tunggu Terlama Layanan Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="chart-area">
                                <canvas id="chartTerlama" class="mb-3 pb-3"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Waktu Tunggu Terlama Layanan Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="terlamaTabel" width="100%" cellspacing="0">
                                    <thead class="bg bg-teal">
                                        <tr>
                                            <th>Label</th>
                                            <th>Waktu Tunggu (menit)</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table rows will be dynamically populated with data -->
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Data per pasien --}}
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                <h6 class="m-0 font-weight-bold text-primary">Data Waktu Tunggu Layanan Pasien</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body mb-2">
                {{-- <div class="row">
                    <label class="col-form-label">Rentang Tanggal :</label>
                    <div class="form-group col-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" id="reservation">
                        </div>
                    </div>
                </div> --}}
                <div class="table-responsive">
                    <table class="table table-bordered" id="waktuLayanan" width="100%" cellspacing="0">
                        <thead class="bg bg-teal">
                            <tr>
                                <th class="col-1">Antrean Nomor</th>
                                <th class="col-2">Tanggal</th>
                                <th class="col-1">Penjamin</th>
                                <th class="col-1">Daftar Lewat</th>
                                <th class="col-1">No. Rekam Medis</th>
                                <th class="col-3">Nama Pasien</th>
                                <th class="col-1">Poli</th>
                                <th class="col-3">Dokter</th>
                                <th class="col-2">Ambil No Antrian</th>
                                <th class="col-2">Lama Tunggu Pendaftaran (menit)</th>
                                <th class="col-2">Skip Pendafataran</th>
                                <th class="col-2">Panggil Pendafataran</th>
                                <th class="col-2">Selesai Pendaftaran</th>
                                <th class="col-2">Lama Tunggu Tensi (menit)</th>
                                <th class="col-2">Skip Tensi</th>
                                <th class="col-2">Panggil Tensi</th>
                                <th class="col-2">Selesai Tensi</th>
                                <th class="col-2">Lama Tunggu Poli (menit)</th>
                                <th class="col-2">Skip Poli</th>
                                <th class="col-2">Panggil Poli</th>
                                <th class="col-2">Selesai Poli</th>
                                <th class="col-2">Lama Tunggu Lab (menit)</th>
                                <th class="col-2">Skip Lab</th>
                                <th class="col-2">Panggil Lab</th>
                                <th class="col-2">Selesai Lab</th>
                                <th class="col-2">Lama Hasil Lab (menit)</th>
                                <th class="col-2">Lama Tunggu RO (menit)</th>
                                <th class="col-2">Skip RO</th>
                                <th class="col-2">Panggil RO</th>
                                <th class="col-2">Selesai RO</th>
                                <th class="col-2">Lama Hasil RO (menit)</th>
                                <th class="col-2">Lama Tunggu IGD (menit)</th>
                                <th class="col-2">Panggil IGD</th>
                                <th class="col-2">Selesai IGD</th>
                                <th class="col-2">Lama Tunggu Farmasi (menit)</th>
                                <th class="col-2">Panggil Farmasi</th>
                                <th class="col-2">Selesai Farmasi</th>
                                <th class="col-2">Lama Tunggu Kasir (menit)</th>
                                <th class="col-2">Panggil Kasir</th>
                                <th class="col-2">Selesai Kasir</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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

    </body>

    </html>
@endsection
