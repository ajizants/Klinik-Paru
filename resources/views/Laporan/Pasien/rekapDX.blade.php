<div class="card shadow mb-4" id="tab_2" style="display: none">
    <!-- Card Header - Dropdown -->
    <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-start">
        <h6 class="font-weight-bold ">Rekap Diagnosa Kunjungan</h6>
    </div>
    <div class="card-body mb-2">
        <div class="row">
            <label class="col-form-label">Rentang Tanggal :</label>
            <div class="form-group col col-md-3 col-sm-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation">
                </div>
            </div>
            <div class="col-3">
                <button type="button" class="btn btn-success" onclick="cariRiwayat(tglAwal,tglAkhir);">
                    Refresh
                    <span class="fa-solid fa-rotate ml-1" data-toggle="tooltip" data-placement="top" title="Update Data"
                        id="cariantrian"></span>
                </button>
            </div>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable dtr-inline" id="report" cellspacing="0">
                <thead class="bg bg-teal table-bordered border-warning">
                    <tr>
                        <th>Urut</th>
                        <th>Tanggal</th>
                        <th>Penjamin</th>
                        <th>No. RM</th>
                        <th class="col-2">Nama Pasien</th>
                        <th>Desa</th>
                        <th>RT/RW</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>ICD X 1</th>
                        <th>Diagnosa 1</th>
                        <th>ICD X 2</th>
                        <th>Diagnosa 2</th>
                        <th>ICD X 3</th>
                        <th>Diagnosa 3</th>
                        <th class="col-3">Dokter</th>
                        <th class="px-0 col-3">Status</th>
                    </tr>
                </thead>
                <tbody class="table-bordered border-warning">
                </tbody>
            </table>
        </div>

    </div>
</div>
