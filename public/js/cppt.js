let form_id;
function entryCppt(button, noTrans) {
    $.ajax({
        url: `/api/ranap/cppt/getFormId/${noTrans}`,
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    })
        .done(function (response) {
            form_id = response.id;
            const data = response.cpptLast;
            if (data) {
                for (let key in data) {
                    $(`#form_cppt [name="${key}"]`).val(data[key]);
                    $(`#form_cppt [name="${key}"]`).trigger("change");
                }

                $("#subjektif").summernote("code", data.subjektif ?? "");
                $("#objektif").summernote("code", data.objektif ?? "");
                $("#assesment").summernote("code", data.assesment ?? "");
                $("#planing").summernote("code", data.planing ?? "");
                if (data.dx1) isiDiagnosaSelect("#dx1", data.dx1);
                if (data.dx2) isiDiagnosaSelect("#dx2", data.dx2);
                if (data.dx3) isiDiagnosaSelect("#dx3", data.dx3);
                if (data.dx4) isiDiagnosaSelect("#dx4", data.dx4);
            }

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
            document.getElementsByName("notrans")[0].value =
                $(button).data("notrans");

            $("#form_id").val(response.id); // asumsi response bentuk: { id: "..." }

            $("#notrans").val($(button).data("notrans"));
            $("#norm").val($(button).data("norm"));

            $("#tindakan_notrans").val($(button).data("notrans"));
            $("#tindakan_norm").val($(button).data("norm"));
            document.getElementById("tindakan_form_id").value = response.id;
            // $("#form_id").val(response.id);

            $("#penunjang_notrans").val($(button).data("notrans"));
            $("#penunjang_norm").val($(button).data("norm"));
            document.getElementById("penunjang_form_id").value = response.id;
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
            $("#tblBatal").hide();
            $("#tblSelesai").show();
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
        scrollY: "700px", // tinggi maksimum body yang bisa discroll
        scrollCollapse: true,
        paging: false, // opsional, agar tidak ada pagination
        ajax: {
            url: `/api/ranap/cppt/${notrans}`,
            method: "GET",
            dataSrc: "", // karena return-nya array langsung
        },
        columns: [
            {
                data: "form_id",
                render: function (data) {
                    return `
                    <button class="m-2 px-3 btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit CPPT Pasien" onclick="editCPPT('${data}')">
                        <i class="fa fa-edit"></i>
                    </button>
                    <br>
                    <button class="m-2 px-3 btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Hapus CPPT Pasien" onclick="deleteCPPT('${data}')">
                        <i class="fa fa-trash"></i>
                    </button>
                `;
                },
            },
            { data: "created_at" },
            { data: "petugas" },
            { data: "hasil_assessment" },
            { data: "instruksi" },
            { data: "dpjp" },
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
function editCPPT(id) {
    tampilkanLoading("Memuat data...");
    if ($.fn.DataTable.isDataTable("#daftarOrderPenunjang, #dataTindakan")) {
        $("#daftarOrderPenunjang, #dataTindakan").DataTable().destroy();
    }
    $.ajax({
        url: `/api/ranap/cppt/edit/${id}`,
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (res) {
            console.log("✅ Respon:", res);
            const data = res.data[0];
            const form_id = data.form_id;
            const penunjang = res.penunjang;
            const tindakan = res.tindakan;
            //masukan respon ke form_cppt
            for (let key in data) {
                $(`#form_cppt [name="${key}"]`).val(data[key]);
                $(`#form_cppt [name="${key}"]`).trigger("change");
            }

            $("#subjektif").summernote("code", data.subjektif ?? "");
            $("#objektif").summernote("code", data.objektif ?? "");
            $("#assesment").summernote("code", data.assesment ?? "");
            $("#planing").summernote("code", data.planing ?? "");
            if (data.dx1) isiDiagnosaSelect("#dx1", data.dx1);
            if (data.dx2) isiDiagnosaSelect("#dx2", data.dx2);
            if (data.dx3) isiDiagnosaSelect("#dx3", data.dx3);
            if (data.dx4) isiDiagnosaSelect("#dx4", data.dx4);

            $("#tindakan_notrans").val(data.notrans);
            $("#tindakan_norm").val(data.norm);
            document.getElementById("tindakan_form_id").value = form_id;

            $("#penunjang_notrans").val(data.notrans);
            $("#penunjang_norm").val(data.norm);
            document.getElementById("penunjang_form_id").value = form_id;

            isiTabelTindakan(tindakan);
            isiTabelPenunjang(penunjang);
            Swal.close();
            $("#modalRiwayatCppt").modal("hide");
        },
        error: function (xhr) {
            console.error("❌ Error:", xhr.responseText);
        },
    });
}

function isiDiagnosaSelect(selector, kodeDx) {
    $.ajax({
        url: "/api/diagnosa_icd_x",
        data: { search: kodeDx },
        dataType: "json",
        success: function (res) {
            const item = res.find((d) => d.kdDx === kodeDx);
            if (item) {
                const option = new Option(
                    item.kdDx + " - " + item.diagnosa,
                    item.kdDx,
                    true,
                    true
                );
                $(selector).append(option).trigger("change");
            }
        },
    });
}

//penunjang
function handlePilihSemuaClick(pilihSemuaId, checkboxClass) {
    const pilihSemuaCheckbox = document.getElementById(pilihSemuaId);

    if (pilihSemuaCheckbox) {
        pilihSemuaCheckbox.addEventListener("change", function () {
            const isChecked = this.checked;
            const checkboxes = $("." + checkboxClass);

            // Centang atau hapus centang semua checkbox
            checkboxes.prop("checked", isChecked);

            // Hitung total untuk semua checkbox yang dicentang
            hitungTotalSemua(checkboxClass);
        });
    } else {
        console.warn(`Checkbox dengan ID "${pilihSemuaId}" tidak ditemukan.`);
    }
}

function tabelPemeriksaan(itemPemeriksaan, item, pilihSemuaId) {
    // Hapus dan destroy tabel jika sudah diinisialisasi
    if ($.fn.DataTable.isDataTable("#tablePenunjang")) {
        $("#tablePenunjang").DataTable().clear().destroy();
    }

    // Inisialisasi DataTable
    $("#tablePenunjang").DataTable({
        data: itemPemeriksaan,
        autoWidth: true,
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return `
                                <input
                                    type="checkbox"
                                    style="width: 15px; height: 15px; margin-top: 12px;"
                                    class="select-checkbox data-checkbox ${item}"
                                    kdFoto="${row.kdFoto}"
                                    kdTInd="${row.kdTind}"
                                    id="${row.idLayanan}">
                        `;
                },
            },
            {
                data: "nmLayanan",
                render: function (data, type, row) {
                    return `
                        <label
                            for="${row.idLayanan}"
                            class="form-check-label col-form-label col px-0"
                            style="font-size: medium;" type="button">
                            ${data}
                        </label>
                    `;
                },
            },
            {
                data: "nmLayanan",
                render: function (data, type, row) {
                    return `
                        <input
                            type="text"
                            class="form-control mt-1"
                            id="ket_${row.idLayanan}"
                            style="font-size: medium;"
                            placeholder="Ket. untuk ${data}">
                    `;
                },
            },
        ],
        scrollY: "200px",
        order: false,
        paging: false,
    });

    // Inisialisasi handler pilih semua
    handlePilihSemuaClick(pilihSemuaId, `data-checkbox ${item}`);
}
