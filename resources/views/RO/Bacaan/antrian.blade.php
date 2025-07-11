<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show card-body p-0" id="collapseCardAntrian">
        <div class="col-6 d-flex justify-content-center z-3 position-absolute">
        </div>
        <div class="mt-3">
            @include('Template.Table.loading')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#dTunggu');"><b>Daftar
                            Tunggu</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick="toggleSections('#dSelesai');"><b>Daftar Selesai</b></a>
                </li>
                <div class="input-group col d-flex justify-content-end mr-5">
                    <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal"
                        value="{{ date('Y-m-d') }}" required onchange="antrian();" />
                    <div class="input-group-addon btn btn-danger">
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian" onclick="listBacaanRo();"></span>
                    </div>
                </div>
            </ul>
            <div id="dTunggu" class="card-body card-body-hidden p-2">
                <div class="table-responsive pt-2 px-2">
                    <table id="antrianBelum" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-teal">
                            <tr>
                                <th widh="15px">Aksi</th>
                                <th>Hasil</th>
                                <th>Tanggal</th>
                                <th>Jaminan</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>Pemeriksaan</th>
                                <th>Alamat Pasien</th>
                                <th>Dokter</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="dSelesai" class="card-body card-body-hidden p-2" style="display: none;">
                <div class="table-responsive pt-2 px-2">
                    <table id="antrianSudah" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-secondary">
                            <tr>
                                <th widh="15px">Aksi</th>
                                <th>Hasil</th>
                                <th>Tanggal</th>
                                <th>Jaminan</th>
                                <th>No RM</th>
                                <th class="col-2">Nama Pasien</th>
                                <th>Pemeriksaan</th>
                                <th>Alamat Pasien</th>
                                <th>Dokter</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
