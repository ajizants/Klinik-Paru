                <div class="container-fluid">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a type="button" class="nav-link active bg-blue"
                                onclick="toggleSections('#SubKelas');"><b>Kunjungan</b></a>
                        </li>
                        {{-- <li class="nav-item">
                            <a type="button" class="nav-link " onclick="toggleSections('#Kelas');"><b>Kelas</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link " onclick="toggleSections('#Domain'); "><b>Domain</b></a>
                        </li> --}}
                    </ul>
                </div>
                <div class="container-fluid mt-1" id="SubKelas">
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
                                            onclick="cariDataKunjungan($('#reservation').data('daterangepicker').startDate.format('YYYY-MM-DD'), $('#reservation').data('daterangepicker').endDate.format('YYYY-MM-DD'))">Reload</button>
                                    </div>
                                </div>
                                <div class="form-group col">
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
                                                                <li>Untuk mencari data, silahkan pilih rentang tanggal.
                                                                    klik
                                                                    tanggal pertama
                                                                    untuk memilih
                                                                    tanggal
                                                                    awal, klik tanggal kedua untuk memilih tanggal
                                                                    akhir.
                                                                </li>
                                                                <li>Apabila ingin mencari data di 1 tanggal, maka klik 2
                                                                    kali pada tanggal
                                                                    tersebut</li>
                                                                <li>Klik tombol "Pilih" untuk mencari data</li>
                                                                <li>Klik tombol "Reload" untuk memperbarui data</li>
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

                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="Kelas" style="display: none;">
                    <div class="card card-orange">
                        <div class="card-header text-light">
                            <h4 class="card-title">Kelas Diagnosa</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-form"onclick="edit(null, 'form_kelas','Form Tambah Kelas Diagnosa')">Tambah
                                    Kelas Diagnosa</button>
                            </div>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataKelas"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Domain</th>
                                            <th>Kode</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="Domain" style="display: none;">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4 class="card-title">Domain Diagnosa</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-form"
                                    onclick="edit(null, 'form_domain','Form Tambah Domain Diagnosa')">Tambah
                                    Domain</button>
                            </div>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataDomain"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
