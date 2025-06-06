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

                    </div>
                </div>
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <a class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" id="headingOne"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <strong>Klik Untuk Melihat Cara Pencarian Data Pendapatan</strong>
                        </a>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col">
                                        <h5>Pencarian Data Pendapatan Harian per Item</h5>
                                        <ul>
                                            <li>Untuk mencari data, silahkan pilih rentang tanggal. klik tanggal pertama
                                                untuk memilih
                                                tanggal
                                                awal, klik tanggal kedua untuk memilih tanggal akhir.</li>
                                            <li>Apabila ingin mencari data di 1 tanggal, maka klik 2 kali pada tanggal
                                                tersebut</li>
                                        </ul>
                                    </div>
                                    <div class="col">
                                        <h5>Pencarian Data Total Pendapatan Harian</h5>
                                        <ul>
                                            <li>Untuk mencari data pendapatan total Perhari, silahkan pilih tahun terlebih
                                                dahulu
                                            </li>
                                            <li>lalu klik link berikut <a type="button" class="text-primary"
                                                    onclick="reportPendapatanTotalPerHari($('#tahun').val())"> "Cari
                                                    Pendapatan Perhari"</a>, lalu klik tombol "Rekap Total Pendapatan Harian
                                            </li>
                                            <li>Untuk Mencetak data SBS dan BAPH, klik tombol sesuai row tanggal yang ingin
                                                di
                                                cwetak.</li>
                                        </ul>
                                    </div>
                                    <div class="col">
                                        <h5>Pencarian Data STPB dan STS</h5>
                                        <ul>
                                            <li>Untuk mencetak STPB Bruto dan STS Bruto, silahkan Pilih Tahun dan Bulan
                                                terblebih dahulu, lalu klik tombol sesuai data yang di inginkan</li>
                                            <li>Untuk Mencetak Rekap Bulanan, Cukup pilih tahun saja dan klik tombol "Cetak
                                                Rekap Bulanan"
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="col-form-label mr-4">Cetak Data Tahun:</label>
                    <div class="form-group col-7 col-sm-1">
                        <div class="input-group">
                            <Select class="form-control" id="tahun">
                                @foreach ($listYear as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </Select>
                        </div>
                    </div>
                    <label class="col-form-label ml-5 ml-sm-0 pl-5 pl-sm-0">Bulan :</label>
                    <div class="form-group col-7 col-sm-2">
                        <div class="input-group">
                            <Select class="form-control" id="bulan">
                                <option value="">-- Pilih Bulan --</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </Select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-info"
                        onclick="cetakBruto('stsBruto');">
                        Cetak STS Bruto
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-warning"
                        onclick="cetakBruto('stpbBruto');">
                        Cetak STPB Bruto
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-primary"
                        onclick="cetakBruto('rekapBulanan');">
                        Cetak Rekap Bulanan
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-success"
                        onclick="cetakBruto('bkuBruto');">
                        Cetak BKU Bruto
                    </button>
                    <a href="{{ url('/kasir/pendapatan/lain') }}" class="my-1 mx-1 font-weight-bold btn btn-secondary">
                        Register Penutupan Kas
                    </a>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-info"
                        onclick="cetakBruto('retriBruto');">
                        Cetak Lap. Retribusi Bruto
                    </button>
                </div>
                <div class="row mt-4">
                    <label class="col-form-label mb-0">Tampilkan Data di Tanggal:</label>
                    <div class="form-group col-8 col-sm-3 mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" id="reservation">
                        </div>
                    </div>

                    <div class="">
                        <button type="button" class="btn btn-danger" onclick="updateData();">
                            Perbarui Data
                        </button>
                    </div>
                </div>
                <div class="row">
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-outline-primary"
                        onclick="tampilkan('#cardRekapJumlahPasien')">
                        Rekap Jumlah Pasien
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-outline-secondary"
                        onclick="tampilkan('#cardRekapKunjungan')">
                        Rekap Kunjungan Per Pasien
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-outline-success"
                        onclick="tampilkan('#cardTotalPendapatanUmum')">
                        Total Pendapatan Harian
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-outline-info"
                        onclick="tampilkan('#cardPendapatanItemPerhariUmum')">
                        Pendapatan Peritem Harian
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-outline-danger"
                        onclick="tampilkan('#cardTotalPendapatanBPJS')" hidden>
                        Total Pendapatan Harian BPJS
                    </button>
                    <button type="button" class="my-1 mx-1 font-weight-bold btn btn-outline-dark"
                        onclick="tampilkan('#cardPendapatanItemPerhariBpjs')" hidden>
                        Pendapatan Peritem Harian BPJS
                    </button>
                </div>



                <script type="text/javascript">
                    function cetakBruto(tipe) {
                        var tahun = $('#tahun').val();
                        var bulan = $('#bulan').val();
                        if (bulan == '' || tahun == '') {

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Silahkan Pilih Tahun dan Bulan Terlebih Dahulu',
                            }).then(() => {
                                if (document.getElementById("tahun").value == "") {
                                    document.getElementById("tahun").focus();
                                } else {
                                    document.getElementById("bulan").focus();
                                }
                                return false;
                            })
                            return;
                        }
                        if (tipe == 'tutupKas') {

                            var url = `/api/${tipe}/${bulan}/${tahun}`;
                        } else {
                            var url = `/api/${tipe}/${bulan}/${tahun}/umum`;
                        }

                        window.open(url, '_blank');
                    }

                    function tampilkan(sectionToShow) {
                        var sections = [
                            "#cardRekapJumlahPasien",
                            "#cardRekapKunjungan",
                            "#cardPendapatanItemPerhariUmum",
                            "#cardPendapatanItemPerhariBpjs",
                            "#cardTotalPendapatanUmum",
                            "#cardTotalPendapatanBPJS",

                        ];
                        sections.forEach(function(section) {
                            if (section === sectionToShow) {
                                // console.log("🚀 ~ toggleSections ~ sama:", section);
                                $(section).show();
                            } else {
                                // console.log("🚀 ~ toggleSections ~ beda:", section);
                                $(section).hide();
                            }
                        });
                        //buatkan fungsi scrol to section
                        // console.log("🚀 ~ tampilkan ~ sectionToShow:", sectionToShow)
                        $('html, body').animate({
                            scrollTop: $(sectionToShow).offset().top
                        }, 1000);

                    }
                </script>
            </div>
        </div>
    </div>


    {{-- <script>
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
    </script> --}}

    <style>
        #cari {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
    </style>

    <div class="card shadow mb-4" id="cardRekapJumlahPasien" style="display: none">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center bg-success justify-content-center">
            <h5 class="m-0 font-weight-bold">Rekap Jumlah Pasien</h5>
        </div>
        <div class="card-body mb-2">
            <div class="table-responsive" id="tabelJumlah">
            </div>
        </div>
    </div>
    <div class="card shadow mb-4" id="cardRekapKunjungan"style="display: none">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center bg-primary justify-content-center">
            <h6 class="m-0 font-weight-bold">Rekap Kunjungan Kasir</h6>
        </div>
        <div class="card-body mb-2">
            <h3 class="text-center text-primary">Jumlah</h3>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover table-striped" id="reportKunjungan"
                    cellspacing="0">
                    <thead class="bg bg-orange">
                        <tr id="headerRow"></tr>
                    </thead>
                    <tbody class=" ">
                    </tbody>
                    <tfoot>
                        <tr id="footerRow"></tr>
                    </tfoot>
                </table>
            </div>
            <h3 class="text-center text-primary">Rupiah</h3>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover table-striped" id="reportKunjunganRp"
                    cellspacing="0">
                    <thead class="bg bg-info">
                        <tr id="headerRowRp"></tr>
                    </thead>
                    <tbody class=" ">
                    </tbody>
                    <tfoot>
                        <tr id="footerRowRp"></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4" id="cardPendapatanItemPerhariUmum" style="display: none">
        <div class="card-body">
            <div class="form-row">
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
                {{-- </div> --}}
                {{-- <div class="form-row"> --}}
                <div class="col card shadow mb-4 umum">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header  d-flex flex-row align-items-center bg-info justify-content-center">
                        <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Bulan Per Item UMUM</h5>
                    </div>
                    <div class="card-body mb-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabelPerItemUMUMBln" cellspacing="0">
                                <thead class="bg bg-teal table-bordered border-warning">
                                    <tr id="table-header-item">
                                        <th>No</th>
                                        <th>Layanan</th>
                                        {{-- <th class="col-2">Tanggal</th> --}}
                                        <th>Total Rupiah</th>
                                        <th>Total Pasien</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perItem['umumBulanan'] as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['nmLayanan'] }}</td>
                                            {{-- <td>{{ $item['tanggal'] }}</td> --}}
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
            {{-- <div class="form-row"> --}}
            <div class="card shadow mb-4 umum">
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
            {{-- </div> --}}
        </div>
    </div>
    <div class="row" id="cardPendapatanItemPerhariBpjs" style="display: none">
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
    <div class="card shadow mb-4" id="cardTotalPendapatanUmum">
        <!-- Card Header - Dropdown -->
        <div class="card-header d-flex flex-row align-items-center bg-primary justify-content-center">
            <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari UMUM</h5>
        </div>
        <div class="card-body mb-2">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabelPendapatanTotalPerHariUMUM" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Tanggal</th>
                            <th>Nomor SBS</th>
                            <th>Kode Akun</th>
                            <th>Uraian Akun</th>
                            <th>Jumlah</th>
                            <th>Rupiah</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4" id="cardTotalPendapatanBPJS" style="display: none">
        <!-- Card Header - Dropdown -->
        <div class="card-header  d-flex flex-row align-items-center bg-primary justify-content-center">
            <h5 class="m-0 font-weight-bold text-center">Laporan Total Pendapatan Per Hari BPJS</h5>
        </div>
        <div class="card-body mb-2">
            <div class="table-responsive">
                <div class="col-9 mt-4 position-absolute" style="z-index: 100;!important">
                    <button type="button" class="btn btn-success"
                        onclick="reportPendapatanTotalPerHari($('#tahun').val())">
                        Update Data
                    </button>
                </div>
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
        let dataSBSU = dataSBS.umum;
    </script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/reportKasir.js') }}"></script>
@endsection
