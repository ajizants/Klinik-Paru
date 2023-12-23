<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 id="antrianSection" class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse" id="collapseCardAntrian">
        <div class="col-sm-6 d-flex justify-content-center z-5 position-absolute">
            <div class="input-group form-inline col-5">
                <input type="date" class="form-control bg bg-warning" id="tanggal" value="{{ old('date') }}"
                    required>
                <a id="cariantrian" class="input-group-text bg bg-success">
                    <i class="fa-solid fa-rotate py-1"></i>
                </a>
            </div>
        </div>
        <div class="mt-3">
            <div id="loadingSpinner" style="display: none;"
                class="badge bg-primary text-wrap text-center z-3 position-absolute mt-5">
                <i class="fa fa-spinner fa-spin"> </i>Sedang Mencari data...
            </div>
            <div class="card-body card-body-hidden p-2">
                <div class="table-responsive pt-2 px-2">
                    <table id="dataAntrian" class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-teal">
                            <tr>
                                <th width="20px">Aksi</th>
                                <th width="25px">Status</th>
                                <th width="20px">No</th>
                                <th width="40px">NoRM</th>
                                <th width="36px">Layanan</th>
                                <th width="12rem">Nama</th>
                                <th width="20rem">Dokter</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
