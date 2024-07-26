@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
            <h6 class="m-0 font-weight-bold text-primary">Rekap Jumlah Kunjungan</h6>
        </div>
        <!-- Card Body -->
        {{-- <div class="card-body mb-2">
            <div class="row">
                <label class="col-form-label">Rentang Tanggal :</label>
                <div class="form-group col-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="tglJumlah">
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-success" onclick="segarkan();">
                        Segarkan
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top" title="Update Data"
                            id="cariantrian"></span>
                    </button>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="total" width="100%"
                    cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th class="text-center">Jumlah Pasien</th>
                            <th class="text-center">Pasien Batal</th>
                            <th class="text-center">Pasien Skip</th>
                            <th class="text-center">Pasien BPJS</th>
                            <th class="text-center">Pasien UMUM</th>
                            <th class="text-center">Pasien LAMA</th>
                            <th class="text-center">Pasien BARU</th>
                            <th class="text-center">Pasien JKN</th>
                            <th class="text-center">Pasien OTS</th>
                        </tr>
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>
        </div> --}}
        <div class="card-body mb-2">
            <div class="row">
                <label class="col-form-label">Rentang Tanggal :</label>
                <div class="form-group col-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="reservation">
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-success" onclick="segarkan();">
                        Segarkan
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top" title="Update Data"
                            id="cariantrian"></span>
                    </button>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="report" width="120%"
                    cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th>Aksi</th>
                            <th>Urut</th>
                            <th>Tanggal</th>
                            <th>Penjamin</th>
                            <th>Daftar By</th>
                            <th>Ket</th>
                            <th>No. RM</th>
                            <th class="col-2">Nama Pasien</th>
                            <th>JK</th>
                            <th>Umur</th>
                            <th class="col-3">Alamat</th>
                            <th>Poli</th>
                            <th class="col-3">Dokter</th>
                        </tr>
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>
            <div class="container-fluid d-flex justify-content-center">
                <h5>Rekapan Jumlah Pasien </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="total" width="100%"
                    cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th class="text-center">Jumlah Pasien</th>
                            <th class="text-center">Pasien Batal</th>
                            <th class="text-center">Pasien Skip</th>
                            <th class="text-center">Pasien BPJS</th>
                            <th class="text-center">Pasien UMUM</th>
                            <th class="text-center">Pasien LAMA</th>
                            <th class="text-center">Pasien BARU</th>
                            <th class="text-center">Pasien JKN</th>
                            <th class="text-center">Pasien OTS</th>
                        </tr>
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @include('Template.footer')

    </div>
    @include('Template.script')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/reportPendaftaran.js') }}"></script>

    </body>

    </html>
@endsection
