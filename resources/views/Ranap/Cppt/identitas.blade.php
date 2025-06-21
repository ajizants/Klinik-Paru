<div class="card card-lime">
    <div class="card-header">
        <h4 class="card-title">Identitas</h4>
    </div>
    @csrf
    <form class="form-horizontal" id="form_identitas">
        <div class="card-body p-2">
            <div class="form-group form-row"id="inputSection">
                <label for="pasien_no_rm" class="col-sm-75 col-form-label font-weight-bold mb-0 ">No
                    RM</label>
                <div class="col-sm-1 input-group input-group-sm" style="overflow: hidden;">
                    <input readonly type="text" name="pasien_no_rm" id="pasien_no_rm"
                        aria-describedby="inputGroup-sizing-sm" class="form-control" placeholder="No RM" maxlength="6"
                        pattern="[0-9]{6}" />
                </div>
                <label for="pasien_nama" class="col-sm-75 col-form-label font-weight-bold  mb-0">Nama</label>
                <div class="col-sm-2 input-group input-group-sm">
                    <input readonly type="text" id="pasien_nama" class="form-control"
                        aria-describedby="inputGroup-sizing-sm" placeholder="Nama Pasien">
                </div>
                <label for="jaminan" class="col-sm-75 col-form-label font-weight-bold mb-0">Jaminan</label>
                <div class="col-sm-1 input-group input-group-sm">
                    <input readonly type="text" id="jaminan" class="form-control"
                        aria-describedby="inputGroup-sizing-sm" placeholder="jaminan" />
                </div>
                <label for="ruang" class="col-sm-75 col-form-label font-weight-bold mb-0">Ruangan</label>
                <div class="col-sm-1 input-group input-group-sm">
                    <input readonly type="text" id="ruang" class="form-control"
                        aria-describedby="inputGroup-sizing-sm" placeholder="Ruangan" />
                </div>
                <div class="col-sm-50 input-group input-group-sm">
                    <input readonly type="text" id="jenis_kelamin_nama" class="form-control"
                        aria-describedby="inputGroup-sizing-sm" placeholder="JK">
                </div>
                <div class="col-sm-75 input-group input-group-sm">
                    <input readonly type="text" id="umur" class="form-control"
                        aria-describedby="inputGroup-sizing-sm" placeholder="umur" />
                    <div class="input-group-append">
                        <div class="input-group-text" style="height: 31px;"">
                            th
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group form-row mt-2">
                <label for="pasien_tgl_lahir" class="col-sm-75 col-form-label font-weight-bold mb-0">Tgl
                    Lahir</label>
                <div class="col-sm-1 input-group input-group-sm">
                    <input readonly type="text" id="pasien_tgl_lahir" class="form-control"
                        aria-describedby="inputGroup-sizing-sm" placeholder="Tgl Lahir" />
                </div>
                <label for="pasien_notrans" class="col-sm-75 col-form-label font-weight-bold mb-0">NoTrans</label>
                <div class="col-sm-2 input-group input-group-sm">
                    <input readonly type="text" id="pasien_notrans" class="form-control"
                        aria-describedby="inputGroup-sizing-sm" placeholder="Nomor Transaksi" required />
                </div>
                <label for="pasien_alamat" class="col-sm-75 col-form-label font-weight-bold mb-0">Alamat</label>
                <div class="col-sm-4 input-group input-group-sm">
                    <input readonly id="pasien_alamat" class="form-control" aria-describedby="inputGroup-sizing-sm"
                        placeholder="Alamat Pasien" />
                </div>
            </div>
        </div>
    </form>
</div>
