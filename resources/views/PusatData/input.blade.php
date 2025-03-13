                <div class="container-fluid">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a type="button" class="nav-link active bg-blue"
                                onclick="toggleSections('#tab_1');"><b>Kunjungan</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link " onclick="toggleSections('#tab_2');"><b>Faskes
                                    Perujuk</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link " onclick="toggleSections('#tab_3'); "><b>Data IGD</b></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/Laporan/Pendaftaran') }}" class="nav-link "><b>Laporan Pendaftaran</b></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/Laporan/Lab') }}" class="nav-link "><b>Laporan Laboratorium</b></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/Laporan/Lab') }}" class="nav-link "><b>Laporan Radiologi</b></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/kasir/report') }}" class="nav-link "><b>Laporan Kasir</b></a>
                        </li>
                    </ul>
                </div>
                <div class="container-fluid mt-1" id="tab_1">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title font-weight-bold">Data Biaya Kunjungan pasien baru, dan distribusi
                                pasien berkunjung
                                ulang</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <div class="form-group col-3">
                                    {{-- <label class="col-form-label">Rentang Tanggal :</label> --}}
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="reservation">
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#modal-form"
                                            onclick="cariDataKunjungan($('#reservation').data('daterangepicker').startDate.format('YYYY-MM-DD'), $('#reservation').data('daterangepicker').endDate.format('YYYY-MM-DD'))">Cari</button>
                                    </div>
                                </div>
                                <div class="form-group col relative">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <a class="btn btn-link btn-block text-left" type="button"
                                                data-toggle="collapse" id="headingOne" data-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                <strong>Klik Untuk Melihat Cara Pencarian Data</strong>
                                            </a>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <h5>Pencarian Data</h5>
                                                            <ul>
                                                                <li>Untuk mencari data, silahkan pilih tab data yang
                                                                    akan di cari.
                                                                </li>
                                                                <li>Lalu pilih rentang tanggal. klik tanggal pertama
                                                                    untuk memilih tanggal awal, klik tanggal kedua untuk
                                                                    memilih tanggal
                                                                    akhir.
                                                                </li>
                                                                <li>Apabila ingin mencari data di 1 tanggal, maka klik 2
                                                                    kali pada tanggal
                                                                    tersebut</li>
                                                                <li>Klik tombol "Pilih" untuk mencari data</li>
                                                                <li>Klik tombol "Cari" untuk memperbarui data</li>
                                                            </ul>
                                                        </div>
                                                        {{-- <div class="col">
                                                            <h5>Pencarian Data Total Pendapatan Harian</h5>
                                                            <ul>
                                                                <li>Untuk mencari data pendapatan total Perhari,
                                                                    silahkan
                                                                    pilih tahun terlebih
                                                                    dahulu
                                                                </li>
                                                                <li>lalu klik link berikut <a type="button"
                                                                        class="text-primary"
                                                                        onclick="reportPendapatanTotalPerHari($('#tahun').val())">
                                                                        "Cari
                                                                        Pendapatan Perhari"</a>, lalu klik tombol "Rekap
                                                                    Total Pendapatan Harian
                                                                </li>
                                                                <li>Untuk Mencetak data SBS dan BAPH, klik tombol sesuai
                                                                    row
                                                                    tanggal yang ingin
                                                                    di
                                                                    cwetak.</li>
                                                            </ul>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive pt-2 px-2" id="dataKunjungan">
                                <table id="kunjunganTable"
                                    class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                    aria-describedby="kunjunganTable_info">
                                    <thead class="bg-info">
                                        <tr>
                                            <th rowspan="2">No RM</th>
                                            <th class="align-item-center " rowspan="2">Total
                                                Kunjungan</th>
                                            <th class="align-item-center " rowspan="2">Tanggal
                                                Pertama</th>
                                            <th class="align-item-center " rowspan="2">Tanggal
                                                Kedua</th>
                                            <th class="align-item-center " rowspan="2">Kelurahan</th>
                                            <th class="align-item-center " rowspan="2">Kabupaten</th>
                                            <th class="align-item-center " rowspan="2">Tagihan
                                                Baru</th>
                                            <th class="text-center" colspan="3" rowspan="1">Jaminan</th>
                                            <th class="align-item-center " rowspan="2">Datang Lagi
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Saat Baru </th>
                                            <th>Kontrol - Umum</th>
                                            <th>Kontrol - BPJS</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="tab_2" style="display: none;">
                    <div class="card card-orange">
                        <div class="card-header text-light">
                            <h4 class="card-title">Rekap Jumlah Faskes Perujuk</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="form-group col-3">
                                {{-- <label class="col-form-label">Rentang Tanggal :</label> --}}
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="tglFaskesPerujuk">
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-form"
                                        onclick="cariDataFaksesPerujuk($('#tglFaskesPerujuk').data('daterangepicker').startDate.format('YYYY-MM-DD'), $('#tglFaskesPerujuk').data('daterangepicker').endDate.format('YYYY-MM-DD'))">Cari</button>
                                </div>
                            </div>
                            <div class="table-responsive pt-2 px-2" id="dataFaskesPerujuk">
                                <table class="table table-bordered table-hover dataTable dtr-inline"
                                    id="faskesPerujukTable" cellspacing="0">
                                    <thead class="bg bg-orange table-bordered">
                                        <tr>
                                            <th>NO</th>
                                            <th>Nama Faskes</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-bordered">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="tab_3" style="display: none;">
                    <div class="card shadow ">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start bg-teal">
                            <h6 class="m-0 font-weight-bold">Data Kunjungan Pasien IGD Tahun:</h6>
                            <div class="col-md-1 ">
                                <select id="year-selector" class="form-control-sm">
                                    @php
                                        $startYear = 2021; // Tahun mulai
                                        $currentYear = date('Y'); // Tahun saat ini
                                    @endphp
                                    @for ($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-1"></div>
                            <button class="btn btn-success" onclick="getChartData()">Tampilkan</button>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body mb-2">
                            <div class="row  flex-lg-row flex-column">
                                <div class="card shadow mb-4 col">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                                        <h6 class="m-0 font-weight-bold text-primary">Grafik Kunjungan Pasien IGD</h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body mb-2" id="divChartIGD">
                                        <div class="chart-area">
                                            <canvas id="chartIgd" class="mb-3 pb-3"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="card shadow mb-4 col">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                                        <h6 class="m-0 font-weight-bold text-primary">Tabel Kunjungan IGD</h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body mb-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="tabelIgd" width="100%"
                                                cellspacing="0">
                                                <thead class="bg bg-teal">
                                                    <tr>
                                                        <th>Kd</th>
                                                        <th>Bulan</th>
                                                        <th>Layanan</th>
                                                        <th>Jumlah</th>
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
