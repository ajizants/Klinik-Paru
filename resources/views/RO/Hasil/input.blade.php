<div class="card shadow mb-4">
    <div class="card-header bg-lime "">
        <div class="form-group">
            {{-- <label for="norm" class="col-form-label">No RM:</label> --}}
            <div class="d-flex align-items-center">
                <input type="text" class="form-control" id="norm" placeholder="Cari berdasarkan No RM"
                    onkeyup="if (event.keyCode === 13) { cari(); cariLab(); }">
                <button type="button" class="mx-2 btn btn-success" onclick="cari(); cariLab()">Cari</button>
            </div>
        </div>
        <div class="container-fluid col d-flex justify-content-center bg-info p-2" id="identitas"
            style="font-size: 12pt;"></div>
    </div>

    <div class="card-body p-2">
        <!-- Hasil Foto Thorax -->
        <div class="card shadow mb-4">
            <a href="#collapseFotoThorax"
                class="d-block card-header py-1 bg-info text-center text-dark font-weight-bold" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="collapseFotoThorax">
                Hasil Foto Thorax
            </a>
            <div class="collapse show" id="collapseFotoThorax">
                <div class="card-body p-2">
                    <div class="container-fluid py-2">
                        <div class="form-group row d-flex flex-wrap" id="buttondiv"></div>
                    </div>
                    <div class="container-fluid">
                        <div id="preview" class="row d-flex flex-wrap"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Laboratorium -->
        <div class="card shadow mb-4">
            <a href="#cardHasilLab" class="d-block card-header py-1 bg-info text-center text-dark font-weight-bold"
                data-toggle="collapse" role="button" aria-expanded="true" aria-controls="cardHasilLab">
                Hasil Laboratorium
            </a>
            <div class="collapse show" id="cardHasilLab">
                <div class="card-body p-2">
                    <div class="container-fluid">
                        <div id="previewLab" class="row d-flex flex-wrap"></div>
                    </div>
                    <div class="table-responsive pt-2 px-2">
                        <table id="reportKunjungan" class="table table-striped table-hover table-bordered-dark"
                            style="width:100%" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
