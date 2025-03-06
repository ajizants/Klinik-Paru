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
                <button type="button" class="btn btn-success" onclick="segarkan();">
                    Cari Data Jumlah Kujungan
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-warning" onclick="rekapFaskesPerujuk();">
                    Cari Data Faskes Perujuk
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-info" onclick="rencanaKontrolPasien();">
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
                        <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#dTunggu');"><b>Rekap
                                Kunjungan</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#dAntrian');"><b>Rekap Jumlah
                                Kunjungan</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#dSelesai');"><b>Rekap Jumlah Faskes
                                Perujuk</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_1');"><b>Rencana Kontrol
                                Pasien</b></a>
                    </li>

                </ul>
            </div>
            <div class="" id="dTunggu">
                <div class="container-fluid d-flex justify-content-center bg-warning border">
                    <h5 class="m-2"><b>Rekapan Kunjungan</b></h5>
                </div>
                <div class="table-responsive  mt-2">
                    <table class="table table-bordered table-hover dataTable dtr-inline" id="report" cellspacing="0">
                        <thead class="bg bg-teal table-bordered border-warning">
                            <tr>
                                <th>Aksi</th>
                                <th>Urut</th>
                                <th>Tanggal</th>
                                <th>No SEP</th>
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered border-warning">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="" id="dAntrian" style="display: none;">
                <div class="container-fluid d-flex justify-content-center bg-warning border">
                    <h5 class="m-2"><b>Rekapan Jumlah Pasien</b></h5>
                </div>
                <div class="table-responsive  mt-2">
                    <table class="table table-bordered table-hover dataTable dtr-inline" id="total" width="100%"
                        cellspacing="0">
                        <thead class="bg bg-teal table-bordered border-warning">
                            <tr>
                                <th class="text-center">Jumlah No Antri</th>
                                <th class="text-center">Jumlah Pasien</th>
                                <th class="text-center">Pasien Batal</th>
                                <th class="text-center">Pasien Skip</th>
                                <th class="text-center">Pasien BPJS</th>
                                <th class="text-center">Pasien BPJS Per 2</th>
                                <th class="text-center">Pasien UMUM</th>
                                <th class="text-center">Pasien LAMA</th>
                                <th class="text-center">Pasien BARU</th>
                                <th class="text-center">Pasien OTS</th>
                                <th class="text-center">Pasien JKN</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered border-warning">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="" id="tab_1" style="display: none;">
                <div class="container-fluid d-flex justify-content-center bg-warning border">
                    <h5 class="m-2"><b>Rencana Kontrol Pasien</b></h5>
                </div>
                <div class="table-responsive  mt-2" id="divRencanaKontrolTable">
                    <table class="table table-bordered table-hover dataTable dtr-inline" cellspacing="0">
                        <thead class="bg bg-info">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kontrol Selanjutnya</th>
                                <th class="text-center">Jaminan</th>
                                <th class="text-center">No RM</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Alamat</th>
                                <th class="text-center">No HP Pasien</th>
                                <th class="text-center">Penanggung Jawab</th>
                                <th class="text-center">No Hp Penanggung Jawab</th>
                                <th class="text-center">Dokter</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="" id="dSelesai" style="display: none;">
                <div class="container-fluid d-flex justify-content-center bg-warning border">
                    <h5 class="m-2"><b>Rekapan Jumlah Faskes Perujuk</b></h5>
                </div>
                <div class="table-responsive  mt-2">
                    <table class="table table-bordered table-hover dataTable dtr-inline" id="rekapFaskesPerujuk"
                        cellspacing="0">
                        <thead class="bg bg-info table-bordered border-warning">
                            <tr>
                                <th>NO</th>
                                <th>Nama Faskes</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered border-warning">
                        </tbody>
                    </table>
                </div>
            </div>
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
                            <input type="text" class="form-control" id="noSep" required
                                onkeyup="checkEnter(event)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                        onclick="selesai();">Simpan</button>
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
