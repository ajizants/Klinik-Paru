@extends('Template.lte')

@section('content')
    <div class="container-fluid">

        <div class="container-fluid text-center h-100 mb-5">
            <h3>Selamat Datang di Sistem Informasi Pelayanan Rawat Inap</h3>
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

        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    {{-- <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>

    <script src="{{ asset('js/chart.js') }}"></script> --}}
@endsection
