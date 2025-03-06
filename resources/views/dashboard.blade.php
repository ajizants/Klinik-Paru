@extends('Template.lte')

@section('content')
    <div class="container-fluid">

        <div class="container-fluid text-center h-100 mb-5">
            <h3>Selamat Datang di Sistem Informasi Pelayanan Kesehatan</h3>
            <br>
            <h4>Klinik Utama Kesehatan Paru Masyarakat Kelas A</h4>
        </div>


        {{-- Tabel rata rata dan terlama --}}
        <div class="card shadow">
            <div class="card-header bg-info">
                <strong class="font-size-20">Data Kunjungan Pasien</strong>
            </div>
            <div class="card-body">
                <div class="form-group form-row">
                    <Label for="ratawaktulayanan" class="col-form-label font-weight-bold">Pencarian data pasien, Tanggal
                        :</Label>
                    <div class="input-group col-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="ratawaktulayanan">
                    </div>
                </div>
                <div class="row  flex-lg-row flex-column">
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
                                            <th>Keterangan/Label</th>
                                            <th>Total Pasien</th>
                                            <th>Total Waktu Tunggu (menit)</th>
                                            <th>Rata-Rata (menit)</th>
                                            <th>Waktu Tunggu Terlama (menit)</th>
                                            <th>Waktu Tunggu Tercepat (menit)</th>
                                            {{-- <th>Ket</th> --}}
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
                <div class="row  flex-lg-row flex-column">
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Rata-Rata Layanan Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="chart-area">
                                <canvas id="chartAvg" class="mb-3 pb-3" width="100%" height="80%"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Waktu Tunggu Terlama Layanan Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="chart-area">
                                <canvas id="chartTerlama" class="mb-3 pb-3" width="100%" height="80%"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4 col" style="display: none;">
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
                                            <th>Waktu Terlama</th>
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
                <div class="row ">
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Prosentase Capaian Waktu SPM</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="spmTabel" width="100%" cellspacing="0">
                                    <thead class="bg bg-teal">
                                        <tr>
                                            <th>Keterangan/Label</th>
                                            <th>Jumlah Pasien</th>
                                            <th>Jumlah Waktu Tunggu > 90 menit</th>
                                            <th>Prosentase Waktu Tunggu > 90 menit</th>
                                            <th>Jumlah Waktu Tunggu < 90 menit</th>
                                            <th>Prosentase Waktu Tunggu < 90 menit</th>
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
                <div class="row  flex-lg-row flex-column">
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Pemeriksaan Penunjang Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="chart-area">
                                <canvas id="chartPenunjang" class="mb-3 pb-3" width="100%" height="80%"></canvas>
                                <div id="totalPasien" class="text-center mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4 col">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Pemeriksaan Penunjang Pasien</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="penunjangTabel" width="100%" cellspacing="0">
                                    <thead class="bg bg-teal">
                                        <tr>
                                            <th>Label</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table rows will be dynamically populated with data -->
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        {{-- </div> --}}
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="waktuLayanan" width="100%" cellspacing="0">
                            <thead class="bg bg-teal">
                                <tr>
                                    <th class="col-1">No Antri</th>
                                    {{-- <th class="col-2">Tanggal</th> --}}
                                    <th class="col-1">Penjamin</th>
                                    {{-- <th class="col-1">Daftar Lewat</th> --}}
                                    {{-- <th class="col-1">Pendaftaran</th> --}}
                                    <th class="col-1">No. Rekam Medis</th>
                                    <th class="col-3">Nama Pasien</th>
                                    <th class="col-3">JK</th>
                                    <th class="col-3">Umur</th>
                                    {{-- <th class="col-1">Poli</th> --}}
                                    <th class="col-3">Dokter</th>
                                    <th class="col-2">Ambil No Antrian</th>
                                    <th class="col-2">Lama Tunggu Pendaftaran (menit)</th>
                                    <th class="col-2">Skip Pendafataran</th>
                                    <th class="col-2">Panggil Pendafataran</th>
                                    <th class="col-2">Selesai Pendaftaran</th>
                                    <th class="col-2">Selesai RM</th>
                                    <th class="col-2">Lama RM Siap</th>
                                    <th class="col-2">Lama Tunggu Tensi (menit)</th>
                                    <th class="col-2">Skip Tensi</th>
                                    <th class="col-2">Panggil Tensi</th>
                                    <th class="col-2">Selesai Tensi</th>
                                    <th class="col-2">Lama di Tensi (menit)</th>
                                    <th class="col-2">Durasi Tunggu Poli (menit)</th>
                                    <th class="col-2">Lama Tunggu Poli (menit)</th>
                                    <th class="col-2">Skip Poli</th>
                                    <th class="col-2">Panggil Poli</th>
                                    <th class="col-2">Selesai Poli</th>
                                    <th class="col-2">Lama di Poli (menit)</th>
                                    <th class="col-2">Lama Tunggu Lab (menit)</th>
                                    <th class="col-2">Panggil Lab</th>
                                    <th class="col-2">Selesai Lab</th>
                                    <th class="col-2">Lama Hasil Lab (menit)</th>
                                    <th class="col-2">Lama Tunggu RO (menit)</th>
                                    <th class="col-2">Panggil RO</th>
                                    <th class="col-2">Selesai RO</th>
                                    <th class="col-2">Lama Hasil RO (menit)</th>
                                    <th class="col-2">Lama Tunggu IGD (menit)</th>
                                    <th class="col-2">Panggil IGD</th>
                                    <th class="col-2">Selesai IGD</th>
                                    <th class="col-2">Lama IGD</th>
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

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>

    <script src="{{ asset('js/chart.js') }}"></script>
@endsection
