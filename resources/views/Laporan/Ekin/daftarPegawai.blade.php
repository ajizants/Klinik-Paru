<div class="card shadow mb-4" id="tab_1">
    <!-- Card Header - Dropdown -->
    <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-start">
        <h6 class="font-weight-bold ">Rekap Jumlah Diagnosa Kunjungan</h6>
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
                    <input type="text" class="form-control float-right" id="reservation2">
                </div>
            </div>
            <div class="col-3">
                <button type="button" class="btn btn-success" onclick="cariJumlah(tglAwal,tglAkhir);">
                    Cari
                </button>
            </div>

        </div>
        <div class="table-responsive" id="divTablePegawai">
            {!! $tablePegawai !!}
        </div>

    </div>
</div>
