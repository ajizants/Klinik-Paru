@extends('Template.lte')

@section('content')
    <div class="d-flex justify-content-center sticky-top">
        <div class="card shadow col-4 p-0" id="cari2" style="display: none">
            <!-- Card Header - Dropdown -->
            <div class="card-header d-flex flex-row align-items-center bg-warning justify-content-center">
                <h5 class="m-0 font-weight-bold">Pencarian Data</h5>
            </div>
            <div class="card-body py-2">
                <div class="row">
                    <label class="col-form-label mb-0">Rentang Tanggal :</label>
                    <div class="form-group col mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" id="reservation">
                        </div>
                    </div>
                    <div class="col-2 mb-0">
                        <button type="button" class="btn btn-success" onclick="segarkan();">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow p-0" id="cari1">
        <!-- Card Header - Dropdown -->
        <div class="card-header d-flex flex-row align-items-center bg-warning justify-content-center">
            <h5 class="m-0 font-weight-bold">Pencarian Data</h5>
        </div>
        <div class="card-body py-2">
            <p>Untuk mencari data, silahkan pilih rentang tanggal. klik tanggal pertama untuk memilih tanggal awal, klik
                tanggal kedua untuk memilih tanggal akhir.</p>
            <p>Jika ingin mencari data di 1 tanggal, maka klik 2 kali pada tanggal tersebut</p>
            <div class="row d-flex flex-row justify-content-center">
                <label class="col-form-label mb-0">Rentang Tanggal :</label>
                <div class="form-group col-4 mb-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="reservation">
                    </div>
                </div>
                <div class="col-2 mb-0">
                    <button type="button" class="btn btn-success" onclick="segarkan();">
                        Cari
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cari1 = document.getElementById('cari1');
            const cari2 = document.getElementById('cari2');
            const SCROLL_THRESHOLD = 10; // Batas scroll untuk mengganti elemen

            // Pastikan #cari2 tersembunyi di awal
            cari2.style.display = 'none';

            // Fungsi debounce untuk mengoptimalkan scroll event
            function debounce(func, wait = 100) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // Fungsi untuk mengubah elemen berdasarkan scroll
            const handleScroll = debounce(() => {
                if (window.scrollY > SCROLL_THRESHOLD) {
                    cari1.style.display = 'none';
                    cari2.style.display = 'flex';
                } else {
                    cari1.style.display = 'block';
                    cari2.style.display = 'none';
                }
            });

            // Tambahkan event listener scroll
            window.addEventListener('scroll', handleScroll);
        });
    </script>
    <style>
        /* Tambahkan transisi halus untuk perubahan tampilan */
        #cari1,
        #cari2 {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
    </style>

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center bg-success justify-content-center">
            <h5 class="m-0 font-weight-bold">Rekap Jumlah Pasien</h5>
        </div>
        <div class="card-body mb-2">

            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="total" width="100%"
                    cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th class="text-center">Jumlah No Antri</th>
                            <th class="text-center">Jumlah Pasien</th>
                            <th class="text-center">Pasien Batal</th>
                            <th class="text-center">Pasien Skip</th>
                            <th class="text-center">Pasien BPJS</th>
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
    </div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center bg-primary justify-content-center">
            <h6 class="m-0 font-weight-bold">Rekap Kunjungan Kasir</h6>
        </div>
        <div class="card-body mb-2">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="reportKunjungan" cellspacing="0">
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
                        </tr>
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header  d-flex flex-row align-items-center bg-info justify-content-center">
            <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari Per Item</h5>
        </div>
        <div class="card-body mb-2">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="tabelPerItem" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr id="table-header-item">
                            <th>No</th>
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Total Rupiah</th>
                            <th>Total Pasien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perItem as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nmLayanan }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->totalItem }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
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
    <script src="{{ asset('js/reportKasir.js') }}"></script>
@endsection
