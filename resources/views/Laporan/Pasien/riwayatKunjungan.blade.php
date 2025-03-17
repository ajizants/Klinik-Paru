<div class="card shadow mb-4" id="tab_1">
    <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-start">
        <h6 class="font-weight-bold ">Riwayat Kunjungan Pasien</h6>
    </div>
    <div class="card-body">
        <div id="form_cari_riwayat" class="form-row mx-auto">
            <div class="form-group col-10 col-md-5 col-sm-3">
                <input type="text" id="no_rm" class="form-control"
                    placeholder="Ketikan NO RM lalu tekan enter atau klik tombol cari" maxlength="6" pattern="[0-9]{6}"
                    required />
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-success" onclick="cariRiwayatKunjunganPasien();">
                    Cari
                </button>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-body p-2">
                    <div class="container-fluid bg-secondary p-2 pt-4 fs-3" id="identitas">
                        <div class="row">
                            <!-- Kolom 1 -->
                            <div class="col-md-4 col-sm-6 col-12 mb-2">
                                <p><strong>NO RM:</strong> <span>-</span></p>
                                <p><strong>Nama:</strong> <span>-</span></p>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col-md-4 col-sm-6 col-12 mb-2">
                                <p><strong>Tgl Lahir:</strong> <span>-</span></p>
                                <p><strong>Umur:</strong> <span>-</span></p>
                            </div>

                            <!-- Kolom 3 -->
                            <div class="col-md-4 col-sm-6 col-12 mb-2">
                                <p><strong>Kelamin:</strong> <span>-</span></p>
                                <p><strong>Alamat:</strong> <span>-</span></p>
                            </div>
                        </div>
                    </div>

                    <div style="display: block; overflow-x: auto; white-space: nowrap;">
                        <table id="riwayatKunjungan" class="table table-striped table-hover pt-0 mt-0 fs-6"
                            style="width:100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
