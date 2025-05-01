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
                            <a href="{{ url('/Laboratorium/Laporan') }}" class="nav-link "><b>Laporan
                                    Laboratorium</b></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/Radiologi/Laporan') }}" class="nav-link "><b>Laporan Radiologi</b></a>
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
                                <!-- Input Group -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="reservation">
                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#modal-form"
                                                onclick="cariDataKunjungan(
                                                    $('#reservation').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                                                    $('#reservation').data('daterangepicker').endDate.format('YYYY-MM-DD')
                                                )">Cari</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordion -->
                                <div class="col-md-8">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <a class="btn btn-link text-left w-100" type="button"
                                                data-toggle="collapse" id="headingOne" data-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                <strong>Klik Untuk Melihat Cara Pencarian Data</strong>
                                            </a>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <h5>Pencarian Data</h5>
                                                    <ul>
                                                        <li>Pilih tab data yang akan dicari.</li>
                                                        <li>Pilih rentang tanggal.</li>
                                                        <li>Untuk satu tanggal, klik dua kali pada tanggal tersebut.
                                                        </li>
                                                        <li>Klik tombol "Pilih" untuk mencari data.</li>
                                                        <li>Klik tombol "Cari" untuk memperbarui data.</li>
                                                    </ul>
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
                            <h6 class="card-title font-weight-bold">Rekap Jumlah Faskes Perujuk</h6>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <!-- Input Group -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="reservation">
                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#modal-form"
                                                onclick="cariDataFaskesPerujuk(
                                                    $('#reservation').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                                                    $('#reservation').data('daterangepicker').endDate.format('YYYY-MM-DD')
                                                )">Cari</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordion -->
                                <div class="col-md-8">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <a class="btn btn-link text-left w-100" type="button"
                                                data-toggle="collapse" id="headingOne" data-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                <strong>Klik Untuk Melihat Cara Pencarian Data</strong>
                                            </a>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <h5>Pencarian Data</h5>
                                                    <ul>
                                                        <li>Pilih tab data yang akan dicari.</li>
                                                        <li>Pilih rentang tanggal.</li>
                                                        <li>Untuk satu tanggal, klik dua kali pada tanggal tersebut.
                                                        </li>
                                                        <li>Klik tombol "Pilih" untuk mencari data.</li>
                                                        <li>Klik tombol "Cari" untuk memperbarui data.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                    <div class="card shadow">
                        <!-- Card Header -->
                        <div class="card-header bg-warning d-flex justify-content-start align-items-center">
                            <h6 class="card-title font-weight-bold mr-4 pt-1 ">Data Kunjungan IGD Tahun: </h6>
                            <div class="d-flex align-items-center">
                                <select id="year-selector" class="form-control form-control-sm mr-2">
                                    @for ($year = date('Y'); $year >= 2021; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <button class="btn btn-sm btn-success" onclick="getChartData()">Cari</button>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row">
                                <!-- Grafik -->
                                <div class="col-lg-6 mb-4">
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Grafik Kunjungan Pasien IGD
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-area">
                                                <canvas id="chartIgd"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabel -->
                                <div class="col-lg-6 mb-4">
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Tabel Kunjungan IGD</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="tabelIgd" width="100%"
                                                    cellspacing="0">
                                                    <thead class="bg-teal">
                                                        <tr>
                                                            <th>Kd</th>
                                                            <th>Bulan</th>
                                                            <th>Layanan</th>
                                                            <th>Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Data akan dimasukkan secara dinamis -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div> <!-- container-fluid -->
