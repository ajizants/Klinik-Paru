<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    {{-- <div class="collapse show collapse show card-body p-0" id="collapseCardAntrian">
        <div class="col-6 d-flex justify-content-center z-3 position-absolute">
        </div>
        <div class="mt-3">
            <div id="loadingSpinner" style="display: none;"
                class="badge bg-primary text-wrap text-center z-6 position-absolute mt-5">
                <i class="fa fa-spinner fa-spin"></i> Sedang Mencari data...
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="itunggu"><b>Tunggu</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="iselesai"><b>Selesai</b></a>
                </li>
                <div class="input-group col d-flex justify-content-start ml-5">
                    <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal"
                        value="{{ old('date') }}" required>
                    <div class="input-group-addon btn btn-danger">
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian"></span>
                    </div>
                </div>
            </ul>
            <div id="dselesai" class="card-body card-body-hidden p-2">
                <div class="table-responsive pt-2 px-2">
                    <table id="antrianall" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-teal">
                            <tr>
                                <th width="15px">Aksi</th>
                                <th width="25px">Lokasi</th>
                                <th width="15px">No</th>
                                <th width="15px">NoRM</th>
                                <th width="15px">Layanan</th>
                                <th width="36px">Ket</th>
                                <th width="">Nama</th>
                                <th width="">Dokter</th>
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
    </div> --}}

    <div class="iframe-container">
        <iframe src="https://kkpm.banyumaskab.go.id/administrator/ruang_poli/menu_poli?poli_sub_id=1" frameborder="0"
            allowfullscreen></iframe>
    </div>
</div>
