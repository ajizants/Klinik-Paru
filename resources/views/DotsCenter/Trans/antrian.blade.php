<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 id="antrianSection" class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show card-body p-0" id="collapseCardAntrian">
        <div class="col-sm-4 d-flex justify-content-center position-absolute">
            <div class="input-group col d-flex justify-content-center">
                <input type="date" class="form-control col-sm-4 bg bg-warning" id="tanggal"
                    value="{{ old('date') }}" required onchange="updateAntrian();">
                <div class="input-group-addon btn btn-danger">
                    <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                        title="Update Pasien Hari ini" id="cariantrian" onclick="updateAntrian();"></span>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <div class="">
                @include('Template.Table.loading')
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active bg-blue" id="itunggu" type="button"
                            onclick="toggleSections('#dTunggu');"><b>Pasien
                                DX TB</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick=" toggleSections('#dSelesai')"><b>Selesai
                                Input</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" id="iall"
                            onclick="toggleSections('#dAntrian');"><b>Antrian All</b></a>
                    </li>
                    <li class="col"></li>
                    <li class="nav-item">
                        <a class="nav-link" id="ikontrol" type="button"
                            onclick="toggleSections('#dKontrol');"><b>Pasien
                                Tepat Waktu</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" id="itelat" onclick="toggleSections('#dTelat');"><b>Pasien
                                Telat</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" id="ido" onclick="toggleSections('#dDo');"><b>Pasien
                                DO</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" id="itb" onclick="toggleSections('#dTb');"><b>Pasien
                                TB</b></a>
                    </li>
                </ul>

                @include('Template.Table.tunggu')
                @include('Template.Table.selesai')
                @include('Template.Table.all')

                <div id="dKontrol" class="card-body card-body-hidden p-2" style="display: none;">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Pkontrol" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                            cellspacing="0">
                            <thead class="bg bg-success">
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="35px">Hari</th>
                                    <th width="35px">Rencana Kontrol</th>
                                    <th width="40px">Terakhir Kontrol</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">No HP</th>
                                    <th width="36px"class="text-center">Bln Ke</th>
                                    <th width="">Nama</th>
                                    <th width="">Alamat</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="dTelat" class="card-body card-body-hidden p-2" style="display: none;">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Ptelat" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                            cellspacing="0">
                            <thead class="bg bg-warning">
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="35px">Telat</th>
                                    <th width="35px">Rencana Kontrol</th>
                                    <th width="40px">Terakhir Kontrol</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">No HP</th>
                                    <th width="36px"class="text-center">Bln Ke</th>
                                    <th width="">Nama</th>
                                    <th width="">Alamat</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="dDo" class="card-body card-body-hidden p-2" style="display: none;">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Pdo" class="table table-striped table-hover pt-0 mt-0 fs-6"
                            style="width:100%" cellspacing="0">
                            <thead class="bg bg-danger">
                                <tr>
                                    <th width="15px">Aksi</th>
                                    <th width="35px">Telat</th>
                                    <th width="35px">Rencana Kontrol</th>
                                    <th width="40px">Terakhir Kontrol</th>
                                    <th width="15px"class="text-center">No</th>
                                    <th width="15px" class="text-center">NoRM</th>
                                    <th width="15px"class="text-center">No HP</th>
                                    <th width="36px"class="text-center">Bln Ke</th>
                                    <th width="">Nama</th>
                                    <th width="">Alamat</th>
                                    <th width="">Dokter</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="dTb" class="card-body card-body-hidden p-2"style="display: none;">
                    <div class="table-responsive pt-2 px-2">
                        <table id="Ptb" class="table table-striped table-hover pt-0 mt-0 fs-6"
                            style="width:100%" cellspacing="0">
                            <thead class="bg bg-info">
                                <tr>
                                    <th>Aksi</th>
                                    <th>Mulai</th>
                                    <th class="text-center">No</th>
                                    <th class="text-center">NoRM</th>
                                    <th class="text-center">No HP</th>
                                    <th class="text-center">NIK</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Dokter</th>
                                    <th class="text-center">Status Pengobatan</th>
                                    <th class="text-center">Hasil Pengobatan</th>
                                    <th class="text-center">Diagnosa</th>
                                    <th>Ket</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
