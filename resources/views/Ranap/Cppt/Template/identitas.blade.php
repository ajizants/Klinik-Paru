<div class="card card-lime">
    <div class="card-header">
        <h4 class="card-title">Identitas</h4>
    </div>
    @csrf
    <form class="form-horizontal" id="form_identitas">
        <div class="card-body p-2">
            <div class="form-group form-row" id="inputSection">
                <label for="pasien_no_rm" class="col-sm-75 col-form-label font-weight-bold mb-0">No RM</label>
                <div class="col-sm-1 input-group input-group-sm" style="overflow: hidden;">
                    <input type="text" name="pasien_no_rm" id="pasien_no_rm" class="form-control" placeholder="No RM"
                        maxlength="6" pattern="[0-9]{6}" required />
                </div>

                <label for="pasien_nama" class="col-sm-75 col-form-label font-weight-bold mb-0">Nama</label>
                <div class="col-sm-2 input-group input-group-sm">
                    <input readonly type="text" name="pasien_nama" id="pasien_nama" class="form-control"
                        placeholder="Nama Pasien">
                </div>

                <label for="jaminan" class="col-sm-75 col-form-label font-weight-bold mb-0">Jaminan</label>
                <div class="col-sm-1 input-group input-group-sm">
                    <input readonly type="text" name="jaminan" id="jaminan" class="form-control"
                        placeholder="Jaminan" />
                </div>

                <label for="ruang" class="col-sm-75 col-form-label font-weight-bold mb-0">Ruangan</label>
                <div class="col-sm-1 input-group input-group-sm">
                    <input readonly type="text" name="ruang" id="ruang" class="form-control"
                        placeholder="Ruangan" />
                </div>

                <div class="col-sm-50 input-group input-group-sm">
                    <input readonly type="text" name="jenis_kelamin_nama" id="jenis_kelamin_nama"
                        class="form-control" placeholder="JK">
                </div>

                <div class="col-sm-75 input-group input-group-sm">
                    <input readonly type="text" name="umur" id="umur" class="form-control"
                        placeholder="Umur" />
                    <div class="input-group-append">
                        <div class="input-group-text" style="height: 31px;">th</div>
                    </div>
                </div>
            </div>

            <div class="form-group form-row mt-2">
                <label for="pasien_tgl_lahir" class="col-sm-75 col-form-label font-weight-bold mb-0">Tgl Lahir</label>
                <div class="col-sm-1 input-group input-group-sm">
                    <input readonly type="text" name="pasien_tgl_lahir" id="pasien_tgl_lahir" class="form-control"
                        placeholder="Tgl Lahir" />
                </div>

                <label for="notrans" class="col-sm-75 col-form-label font-weight-bold mb-0">NoTrans</label>
                <div class="col-sm-2 input-group input-group-sm">
                    <input readonly type="text" name="notrans" id="pasien_notrans" class="form-control"
                        placeholder="Nomor Transaksi" required />
                </div>

                <label for="pasien_alamat" class="col-sm-75 col-form-label font-weight-bold mb-0">Alamat</label>
                <div class="col-sm-4 input-group input-group-sm">
                    <input readonly name="pasien_alamat" id="pasien_alamat" class="form-control"
                        placeholder="Alamat Pasien" />
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    window.addEventListener("load", function() {
        $("#pasien_no_rm").on("keyup", function(event) {

            if (event.key === "Enter") {
                event.preventDefault();
                cariPasienRanap(($("#pasien_no_rm").val()).padStart(6, '0'));
            }
        });
    });

    function cariPasienRanap(no_rm) {
        tampilkanLoading("Sedangan mengambil data pasien...");
        let norm = no_rm;
        $.ajax({
            url: `/api/ranap/pasien/${norm}`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function(res) {
                const pasien = res.pasien;
                const notrans = pasien.notrans;
                console.log("🚀 ~ cariPasienRanap ~ notrans:", notrans)
                const norm = pasien.pasien_no_rm;
                console.log("🚀 ~ cariPasienRanap ~ norm:", norm)
                const form_id = res.form_id;
                console.log("🚀 ~ cariPasienRanap ~ form_id:", form_id)


                $("#form_id").val(form_id); // asumsi response bentuk: { id: "..." }

                document.getElementById("notrans").value = notrans;
                document.getElementById("norm").value = norm;

                document.getElementById("tindakan_notrans").value = notrans;
                document.getElementById("tindakan_norm").value = norm;
                document.getElementById("tindakan_form_id").value = form_id;

                document.getElementById("penunjang_notrans").value = notrans;
                document.getElementById("penunjang_norm").value = norm;
                document.getElementById("penunjang_form_id").value = form_id;
                for (let key in pasien) {
                    $(`#form_identitas [name="${key}"]`).val(pasien[key]);
                    $(`#form_identitas [name="${key}"]`).trigger("change");
                }

                Swal.close();
            },
            error: function(xhr) {
                console.error("❌ Error:", xhr.responseText);
            },
        });
    }
</script>
