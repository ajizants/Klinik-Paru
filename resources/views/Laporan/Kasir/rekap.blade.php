@extends('Template.lte')

@section('content')
    <div class="" id="cari">
        <div class="card shadow p-0" id="cardCari">
            <!-- Card Header - Dropdown -->
            <div class="card-header d-flex flex-row align-items-center bg-warning justify-content-center  py-1">
                <h5 class="m-0 font-weight-bold">Pencarian Data</h5>
            </div>
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-6 info" id="info">
                        <ul>
                            <li>Untuk mencari data, silahkan pilih rentang tanggal. klik tanggal pertama untuk memilih
                                tanggal
                                awal, klik tanggal kedua untuk memilih tanggal akhir.</li>
                            <li>Apabila ingin mencari data di 1 tanggal, maka klik 2 kali pada tanggal tersebut</li>
                        </ul>
                    </div>
                    <div class="col-1 info">
                    </div>
                    <div class="col">
                        <div class="row">
                            <label class="col-form-label mb-0">Tanggal :</label>
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
                                <button type="button" class="btn btn-success" onclick="updateData();">
                                    Cari
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3 d-flex flex-row justify-content-center">
                            <button class="btn btn-info" type="button" onclick="showSelector('.umum','.bpjs')">
                                UMUM
                            </button>
                            <div class="col-1">
                            </div>
                            <button class="btn btn-warning" type="button" onclick="showSelector('.bpjs','.umum')">
                                BPJS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cari = document.getElementById('cari');
            const info = document.getElementById('info');
            const cardCari = document.getElementById('cardCari');
            if (!cari) {
                console.error("Elemen dengan ID 'cari' tidak ditemukan di DOM.");
                return;
            }

            const SCROLL_THRESHOLD = 10; // Batas scroll untuk mengganti elemen

            // Fungsi debounce untuk mengoptimalkan scroll event
            const debounce = (func, wait = 100) => {
                let timeout;
                return (...args) => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func(...args), wait);
                };
            };

            // Fungsi untuk mengubah elemen berdasarkan scroll
            const handleScroll = debounce(() => {
                if (window.scrollY > SCROLL_THRESHOLD) {
                    cari.classList.add('d-flex', 'justify-content-end', 'sticky-top');
                    cardCari.classList.add('col-4');
                    // info.style.display = 'none';
                    document.querySelectorAll(".info").forEach(function(element) {
                        element.style.display = 'none';
                    });
                } else {
                    cari.classList.remove('d-flex', 'justify-content-end', 'sticky-top');
                    cardCari.classList.remove('col-4');
                    // info.style.display = 'block';
                    document.querySelectorAll(".info").forEach(function(element) {
                        element.style.display = 'block';
                    });
                }
            });

            // Tambahkan event listener scroll
            window.addEventListener('scroll', handleScroll);
        });
    </script>

    {{-- <div class="card shadow p-0" id="cari1">
        <!-- Card Header - Dropdown -->
        <div class="card-header d-flex flex-row align-items-center bg-warning justify-content-center">
            <h5 class="m-0 font-weight-bold">Pencarian Data</h5>
        </div>
        <div class="card-body py-2">
            <div class="row">
                <div class="col">
                    <ul>
                        <li>Untuk mencari data, silahkan pilih rentang tanggal. klik tanggal pertama untuk memilih tanggal
                            awal, klik tanggal kedua untuk memilih tanggal akhir.</li>
                        <li>Apabila ingin mencari data di 1 tanggal, maka klik 2 kali pada tanggal tersebut</li>
                    </ul>
                </div>
                <div class="col-4">
                    <div class="row d-flex flex-row justify-content-center">
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
                            <button type="button" class="btn btn-success" onclick="updateData();">
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}
    {{-- <script>
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
    </script> --}}
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
                <table class="table table-bordered table-hover" id="total" width="100%" cellspacing="0">
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
                <table class="table table-bordered table-hover" id="reportKunjungan" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col card shadow mb-4 umum">
            <!-- Card Header - Dropdown -->
            <div class="card-header  d-flex flex-row align-items-center bg-info justify-content-center">
                <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari Per Item UMUM</h5>
            </div>
            <div class="card-body mb-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabelPerItemUMUM" cellspacing="0">
                        <thead class="bg bg-teal table-bordered border-warning">
                            <tr id="table-header-item">
                                <th>No</th>
                                <th>Layanan</th>
                                <th class="col-2">Tanggal</th>
                                <th>Total Rupiah</th>
                                <th>Total Pasien</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perItem['umum'] as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['nmLayanan'] }}</td>
                                    <td>{{ $item['tanggal'] }}</td>
                                    <td>Rp. {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                    <td>{{ $item['totalItem'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col card shadow mb-4 umum">
            <!-- Card Header - Dropdown -->
            <div class="card-header  d-flex flex-row align-items-center bg-info justify-content-center">
                <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari Per Ruang UMUM</h5>
            </div>
            <div class="card-body mb-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabelPerRuangUMUM" cellspacing="0">
                        <thead class="bg bg-teal table-bordered border-black">
                            <tr id="table-header-item">
                                <th>No</th>
                                <th>Ruangan/Grup</th>
                                <th class="col-2">Tanggal</th>
                                <th>Total Rupiah</th>
                                <th>Total Pasien</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perRuang['umum'] as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['nmKelas'] }}</td>
                                    <td>{{ $item['tanggal'] }}</td>
                                    <td>Rp. {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                    <td>{{ $item['totalItem'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col card shadow mb-4 bpjs">
            <!-- Card Header - Dropdown -->
            <div class="card-header  d-flex flex-row align-items-center bg-info justify-content-center">
                <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari Per Item BPJS</h5>
            </div>
            <div class="card-body mb-2">
                <div class="table-responsive pjs">
                    <table class="table table-bordered table-hover" id="tabelPerItemBPJS" cellspacing="0">
                        <thead class="bg bg-teal table-bordered border-warning">
                            <tr id="table-header-item">
                                <th>No</th>
                                <th>Layanan</th>
                                <th class="col-2">Tanggal</th>
                                <th>Total Rupiah</th>
                                <th>Total Pasien</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perItem['bpjs'] as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['nmLayanan'] }}</td>
                                    <td>{{ $item['tanggal'] }}</td>
                                    <td>Rp. {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                    <td>{{ $item['totalItem'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col card shadow mb-4 bpjs">
            <!-- Card Header - Dropdown -->
            <div class="card-header  d-flex flex-row align-items-center bg-info justify-content-center">
                <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari Per Ruang BPJS</h5>
            </div>
            <div class="card-body mb-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabelPerRuangBPJS" cellspacing="0">
                        <thead class="bg bg-teal table-bordered border-black">
                            <tr id="table-header-item">
                                <th>No</th>
                                <th>Ruangan/Grup</th>
                                <th>Tanggal</th>
                                <th>Total Rupiah</th>
                                <th>Total Pasien</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perRuang['bpjs'] as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['nmKelas'] }}</td>
                                    <td>{{ $item['tanggal'] }}</td>
                                    <td>Rp. {{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                                    <td>{{ $item['totalItem'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header  d-flex flex-row align-items-center bg-primary justify-content-center">
            <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari</h5>
        </div>
        <div class="card-body mb-2">
            <div class="row">
                <label class="col-form-label">Tahun :</label>
                <div class="form-group col-3">
                    <div class="input-group">
                        <Select class="form-control" id="tahun">
                            @foreach ($listYear as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </Select>
                    </div>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-success" onclick="report();">
                        Cari
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top" title="Update Data"
                            id="cariantrian"></span>
                    </button>
                </div>

            </div>
            <div class="table-responsive umum">
                <h3>UMUM</h3>
                <table class="table table-bordered table-hover" id="tabelPendapatanTotalPerHariUMUM" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th>Aksi</th>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor SBS</th>
                            <th>Kode Akun</th>
                            <th>Uraian Akun</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive bpjs">
                <h3>BPJS</h3>
                <table class="table table-bordered table-hover" id="tabelPendapatanTotalPerHariBPJS" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th>Aksi</th>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor SBS</th>
                            <th>Kode Akun</th>
                            <th>Uraian Akun</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- my script -->
    <script>
        let dataSBS = @json($pendapatanTotal);
        let dataSBSB = dataSBS.bpjs;
        console.log("🚀 ~ dataSBSB:", dataSBSB)
        let dataSBSU = dataSBS.umum;
        console.log("🚀 ~ dataSBSU:", dataSBSU)

        function showSelector(idShow, idHide) {
            console.log("🚀 ~ showSelector ~ idShow:", idShow);
            console.log("🚀 ~ showSelector ~ idHide:", idHide);

            // Tampilkan elemen dengan idShow
            document.querySelectorAll(idShow).forEach(function(element) {
                element.style.display = 'block';
            });

            // Sembunyikan elemen dengan idHide
            document.querySelectorAll(idHide).forEach(function(element) {
                element.style.display = 'none';
            });
        }

        // Contoh penggunaan
        showSelector('.umum', '.bpjs');
    </script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/reportKasir.js') }}"></script>
@endsection
