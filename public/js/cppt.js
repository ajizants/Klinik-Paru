function entryCppt(button, noTrans) {
    $.ajax({
        url: `/api/ranap/cppt/getFormId`,
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    })
        .done(function (response) {
            $("#form_id").val(response.id); // asumsi response bentuk: { id: "..." }
            $("#pasien_no_rm").val($(button).data("norm"));
            $("#jaminan").val($(button).data("jaminan"));
            $("#pasien_nama").val($(button).data("nama"));
            $("#jenis_kelamin_nama").val($(button).data("jk"));
            $("#pasien_tgl_lahir").val($(button).data("tgllahir"));
            $("#pasien_notrans").val($(button).data("notrans"));
            $("#pasien_alamat").val($(button).data("alamat"));
            $("#umur").val($(button).data("umur"));
            $("#ruang").val($(button).data("ruang"));
            $("#petugas").val($(button).data("dpjp"));
            $("#petugas").trigger("change");

            $("#notrans").val($(button).data("notrans"));
            $("#norm").val($(button).data("norm"));

            $("#tindakan_notrans").val($(button).data("notrans"));
            $("#tindakan_norm").val($(button).data("norm"));
            scrollToInputSection();
        })
        .fail(function (xhr) {
            console.error("Gagal mengambil form ID:", xhr);
            tampilkanEror("Gagal mengambil data form");
        });
}

function simpanCppt() {
    const form = $("#form_cppt")[0];
    const formData = new FormData(form);

    $.ajax({
        url: "/api/ranap/cppt",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            console.log("✅ Respon:", res);
            Swal.fire("Sukses", "Data berhasil disimpan", "success");
            // loadCpptTable(res.notrans);
        },
        error: function (xhr) {
            console.error("❌ Error:", xhr.responseText);
            Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan", "error");
        },
    });
}

function loadCpptTable(notrans) {
    notrans = notrans || $("#pasien_notrans").val();

    if (notrans === "") {
        tampilkanEror("Pilih Pasien dahulu");
        return;
    }

    $("#tabel_riwayat_cppt").DataTable({
        destroy: true,
        processing: true,
        serverSide: false,
        ajax: {
            url: `/api/ranap/cppt/${notrans}`,
            method: "GET",
            dataSrc: "", // karena return-nya array langsung
        },
        columns: [
            {
                data: "id",
                render: function (data) {
                    return `
                        <button class="btn btn-sm btn-danger" onclick="deleteCPPT('${data}')">
                            <i class="fa fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="editCPPT('${data}')">
                            <i class="fa fa-edit"></i>
                        </button>
                    `;
                },
            },
            { data: "created_at" },
            { data: "petugas" },
            { data: "hasil_assessment" },
            { data: "instruksi" },
            { data: "dpjp" }, // Review DPJP sama dengan petugas
        ],
    });
}

function deleteCPPT(id) {
    $.ajax({
        url: `/api/ranap/cppt/${id}`,
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            console.log("✅ Respon:", res);
            loadCpptTable();
        },
        error: function (xhr) {
            console.error("❌ Error:", xhr.responseText);
        },
    });
}
