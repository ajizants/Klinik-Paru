function entryCppt(button, noTrans) {
    $("#pasien_no_rm").val($(button).data("norm"));
    $("#jaminan").val($(button).data("jaminan"));
    $("#pasien_nama").val($(button).data("nama"));
    $("#jenis_kelamin_nama").val($(button).data("jk"));
    $("#pasien_tgl_lahir").val($(button).data("tgllahir"));
    $("#pasien_notrans").val($(button).data("notrans"));
    $("#pasien_alamat").val($(button).data("alamat"));
    $("#umur").val($(button).data("umur"));
    $("#ruang").val($(button).data("ruang"));

    $("#notrans").val($(button).data("notrans"));
    $("#norm").val($(button).data("norm"));
    scrollToInputSection();
}

function simpanCppt() {
    const form = document.getElementById("form_cppt");
    const formData = new FormData(form);

    // Debug isi
    console.log("🚀 ~ simpanCppt ~ isi catatan:", formData.get("catatan"));

    fetch("/api/ranap/cppt", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="_token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log("✅ Berhasil:", data);
            Swal.fire("Sukses", "Data berhasil disimpan", "success");
        })
        .catch((error) => {
            console.error("❌ Gagal:", error);
            Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan", "error");
        });
}
