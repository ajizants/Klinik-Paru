<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 id="antrianSection" class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show" id="collapseCardAntrian">
        <div class="col-sm-4 d-flex justify-content-center position-absolute">
            <div class="input-group form-inline col-5">
                <input type="date" class="form-control bg bg-warning" id="tanggal" value="{{ old('date') }}"
                    required>
                <a id="cariantrian" class="input-group-text bg bg-success">
                    <i class="fa-solid fa-rotate py-1"></i>
                </a>
            </div>
        </div>
        <div class="mt-5">
            <div class="">
                <div id="loadingSpinner" style="display: none;"
                    class="badge bg-primary text-wrap text-center z-3 position-absolute mt-5">
                    <i class="fa fa-spinner fa-spin"></i> Sedang Mencari data...
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="ikontrol"><b>Pasien Kontrol</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="itelat"><b>Pasien Telat</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ido"><b>Pasien DO</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="itb"><b>Pasien TB</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="iall"><b>Paien Hari Ini</b></a>
                    </li>
                </ul>
                <div id="dkontrol" class="card-body card-body-hidden p-2">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Pkontrol" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                            cellspacing="0">
                            <thead class="bg bg-teal">
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="35px">Status</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">No HP</th>
                                    <th width="36px"class="text-center">Ket</th>
                                    <th width="">Nama</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="dtelat" class="card-body card-body-hidden p-2">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Ptelat" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="35px">Telat</th>
                                    <th width="35px">Kontrol</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">No HP</th>
                                    <th width="">Nama</th>
                                    <th width="">Alamat</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="ddo" class="card-body card-body-hidden p-2">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Pdo" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="35px">Telat</th>
                                    <th width="35px">Kontrol</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">No HP</th>
                                    <th width="36px"class="text-center">Bln Ke</th>
                                    <th width="">Nama</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="dtb" class="card-body card-body-hidden p-2">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Ptb" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="35px">Mulai</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">No HP</th>
                                    <th width="36px"class="text-center">Status</th>
                                    <th width="">Nama</th>
                                    <th width="">Alamat</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="dselesai" class="card-body card-body-hidden p-2">
                    <div class="table-responsive pt-2 px-2">
                        <table id="antrianall" class="table table-striped table-hover pt-0 mt-0 fs-6"
                            style="width:100%" cellspacing="0">
                            <thead class="bg bg-teal">
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="25px">Lokasi</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">Layanan</th>
                                    <th width="36px"class="text-center">Ket</th>
                                    <th width="">Nama</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
