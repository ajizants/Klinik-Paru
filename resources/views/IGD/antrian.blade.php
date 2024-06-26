<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show collapse show card-body p-0" id="collapseCardAntrian">
        <div class="col-6 d-flex justify-content-center z-3 position-absolute">
        </div>
        <div class="mt-3">
            <div id="loadingSpinner" style="display: none;"
                class="badge bg-primary text-wrap text-center z-6 position-absolute mt-5">
                <i class="fa fa-spinner fa-spin"></i> Sedang Mencari data...
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link active bg-blue" id="itunggu"><b>Tunggu</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" id="iselesai"><b>Antrian All</b></a>
                </li>
                <div class="input-group col d-flex justify-content-start ml-5">
                    <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal"
                        value="{{ old('date') }}" required onchange="updateAntrian();">
                    <div class="input-group-addon btn btn-danger">
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian" onclick="updateAntrian();"></span>
                    </div>
                </div>
            </ul>
            <div id="dselesai" class="card-body card-body-hidden p-2" style="display: none;">
                <div class="table-responsive pt-2 px-2">
                    <table id="antrianall" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-warning">
                            <tr>
                                <th width="15px">Aksi</th>
                                <th width="25px">Status</th>
                                <th>Tanggal</th>
                                <th>Urut</th>
                                <th>Jaminan</th>
                                <th>No RM</th>
                                <th class="col-3">Nama Pasien</th>
                                <th>Poli</th>
                                <th class="col-3">Dokter</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="dtunggu" class="card-body card-body-hidden p-2">
                <div class="table-responsive pt-2 px-2">
                    <table id="dataAntrian" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-teal">
                            <tr>
                                <th width="20px">Aksi</th>
                                <th width="25px">Status</th>
                                <th width="20px"class="text-center">No</th>
                                <th width="25px" class="text-center">NoRM</th>
                                <th width="36px"class="text-center">Layanan</th>
                                <th width="12rem">Nama</th>
                                <th width="20rem">Tindakan</th>
                                <th width="20rem">Dokter</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
