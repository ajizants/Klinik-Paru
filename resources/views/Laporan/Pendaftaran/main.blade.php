@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="container-fluid">
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
            <div class="mx-2">
                <button type="button" class="btn btn-success" onclick="segarkan(); toggleSections('#tab_1');">
                    Cari Data Jumlah Kujungan
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-warning" onclick="rekapFaskesPerujuk(); toggleSections('#tab_3');">
                    Cari Data Faskes Perujuk
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-info" onclick="rencanaKontrolPasien(); toggleSections('#tab_4');">
                    Cari Data Rencana Kontrol
                </button>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row ml-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#tab_1');"><b>Rekap
                                Kunjungan</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_2');"><b>Rekap Jumlah
                                Kunjungan</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_3');"><b>Rekap Jumlah Faskes
                                Perujuk</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_4');"><b>Rencana Kontrol
                                Pasien</b></a>
                    </li>

                </ul>
            </div>
            @include('Laporan.Pendaftaran.kunjungan')
            @include('Laporan.Pendaftaran.jumlahKunjungan')
            @include('Laporan.Pendaftaran.faskesPerujuk')
            @include('Laporan.Pendaftaran.rencanaKontrol')
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalSep" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalSepLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSepLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formSep">
                        <div class="form-group">
                            <label for="norm">No. RM</label>
                            <input type="text" class="form-control" id="norm" readonly>
                            <input type="text" class="form-control" id="notrans" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jaminan">Jaminan</label>
                            <input type="text" class="form-control" id="jaminan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="noSep">No. SEP</label>
                            <input type="text" class="form-control" id="noSep" required onkeyup="checkEnter(event)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="selesai();">Simpan</button>
                    <button type="button" class="btn btn-danger"data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/reportPendaftaran.js') }}"></script>
@endsection
