var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

async function cariTsLab(norm, tgl) {
    formatNorm($("#norm"));
    norm = norm || $("#norm").val();
    tgl = tgl || $("#tanggal").val();
    var requestData = { norm: norm, tgl: tgl };

    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data pasien...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    try {
        const response = await fetch("/api/cariTsLab", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
        });

        if (!response.ok) {
            if (response.status == 404) {
                // searchRMObat(norm);
                cariKominfo(norm, tgl);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
                throw new Error("Network response was not ok");
            }
        } else {
            const data = await response.json();
            $("#norm").val(data.norm);
            $("#nama").val(data.nama);
            $("#nik").val(data.nik);
            $("#alamat").val(data.alamat);
            $("#notrans").val(data.notrans);
            $("#layanan").val(data.layanan);
            $("#dokter").val(data.dokter).trigger("change");
            $("#analis").val(data.petugas).trigger("change");
            //masukan nilai cretaed_at (timestamp) ke dalam element #tgltrans
            // $("#tgltrans").val(data.created_at); //kenapa kosong, type element date

            const notrans = data.notrans;
            console.log("🚀 ~ cariTsLab ~ notrans:", notrans);
            var pemeriksaan = data.pemeriksaan;
            dataLab(pemeriksaan);
            Swal.close();
        }
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
        });
    }
}
async function dataLab(pemeriksaan, notrans) {
    if ($.fn.DataTable.isDataTable("#inputHasil")) {
        var table = $("#inputHasil").DataTable();
        table.destroy();
    }

    try {
        var data = pemeriksaan;
        console.log("🚀 ~ dataLab ~ data:", data);
        const analisResponse = await fetch("/api/analis", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });

        if (!analisResponse.ok) {
            throw new Error(`HTTP error! Status: ${analisResponse.status}`);
        }

        const analisData = await analisResponse.json();

        data.forEach((item, index) => {
            item.no = index + 1;
            //jika hasil null maka kembalikan ""
            item.hasiLab = item.hasil == null ? "" : item.hasil;
            item.actions = `<a class="delete"
                                data-id="${item.idLab}"
                                data-layanan="${item.pemeriksaan.nmLayanan}"
                                data-analis="${item.petugas}"
                                data-hasil="${item.hasil}"
                                onclick="deletLab();"><i class="fas fa-trash"></i></a>`;
        });

        $("#inputHasil").DataTable({
            data: data,
            columns: [
                // { data: "actions", className: "px-0 col-1 text-center" },
                {
                    data: "no",
                    render: function (data, type, row) {
                        return `<p type="text" class="form-control-sm col hasil" >${data}</p>`;
                    },
                },

                {
                    data: "norm",
                    render: function (data, type, row) {
                        return `<p type="text" class="form-control-sm col hasil" >${data}</p>`;
                    },
                },
                {
                    data: "pemeriksaan.nmLayanan",
                    render: function (data, type, row) {
                        return `<p type="text" class="form-control-sm col hasil" id="layanan${row.idLayanan}" value="${row.idLayanan}" readonly>${data}</p>`;
                    },
                },
                {
                    data: "petugas",
                    render: function (data, type, row) {
                        var inputId = "analis" + row.idLab;
                        var inputField = `<select id="${inputId}" class="form-control-sm col analis">`;
                        inputField += `<option value="">--- Pilih Petugas ---</option>`;
                        analisData.forEach(function (petugas) {
                            var selected =
                                data === petugas.nip ? "selected" : "";
                            inputField += `<option value="${petugas.nip}" ${selected}>${petugas.gelar_d} ${petugas.nama} ${petugas.gelar_b}</option>`;
                        });
                        inputField += "</select>";
                        return inputField;
                    },
                },

                {
                    data: "hasiLab",
                    render: function (data, type, row) {
                        return `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="${data}">`;
                    },
                },
            ],
            order: [1, "asc"],
            scrollY: "320px",
            scrollCollapse: true,
            paging: false,
        });
        scrollToInputSection();
    } catch (error) {
        console.error("Error:", error.message);
    }
}

function simpan() {
    const norm = $("#norm").val();
    const notrans = $("#notrans").val();

    if (!norm || !notrans) {
        const dataKurang = [];
        if (!norm) dataKurang.push("No RM");
        if (!notrans) dataKurang.push("Nomor Transaksi");

        Swal.fire({
            icon: "error",
            title: `Data Tidak Lengkap...!!! ${dataKurang.join(
                ", "
            )} Belum Diisi`,
        });
        return;
    }

    const table = $("#inputHasil").DataTable();
    const dataRows = table.rows().data();

    const dataTerpilih = dataRows
        .map((row) => ({
            idLab: row.idLab,
            idLayanan: row.idLayanan,
            norm: norm,
            notrans: notrans,
            hasil: $("#hasil" + row.idLab).val(),
            petugas: $("#analis" + row.idLab).val(),
        }))
        .toArray();

    fetch("/api/addHasilLab", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ dataTerpilih: dataTerpilih }),
    })
        .then((response) => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then((data) => {
            Swal.fire({
                icon: "success",
                title: "Data berhasil tersimpan...!!!",
            });
            resetForm("Data berhasil tersimpan...!!!");
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
            Swal.fire({
                icon: "error",
                title: `There has been a problem with your fetch operation: ${error}`,
            });
        });
}

function resetForm(message) {
    document.getElementById("form_identitas").reset();

    if ($.fn.DataTable.isDataTable("#inputHasil")) {
        let tableTrans = $("#inputHasil").DataTable();
        tableTrans.clear().destroy();
    }
    Swal.fire({
        icon: "info",
        title: message + "\n Maturnuwun...!!!",
    });

    document.getElementById("tgltrans").value = new Date()
        .toISOString()
        .split("T")[0];
    antrian();
}

function antrian() {
    $("#loadingSpinner").show();
    var tgl = $("#tanggal").val();

    if ($.fn.DataTable.isDataTable("#antrianBelum, #antrianSudah")) {
        $("#antrianBelum, #antrianSudah").DataTable().destroy();
    }

    $.ajax({
        url: "/api/hasil/antrian",
        type: "POST",
        data: { tgl: tgl },
        success: function (response) {
            $("#loadingSpinner").hide();
            var data = response;

            if (Array.isArray(data)) {
                var belumTransaksi = data.filter(function (item) {
                    return (
                        item.status === "Input Hasil Belum Lengkap" ||
                        item.status === "Belum Input Hasil"
                    );
                });

                var sudahTransakasi = data.filter(function (item) {
                    return item.status === "Input Hasil Lengkap";
                });

                antrianBelum(belumTransaksi, tgl);
                antrianSudah(sudahTransakasi, tgl);
            } else {
                console.error("Invalid data format:", data);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function antrianBelum(belumTransaksi, tgl) {
    belumTransaksi.forEach(function (item, index) {
        item.no = index + 1;
        item.tgl = tgl;
        item.tanggal = moment(item.created_at).format("DD-MM-YYYY");
        item.alamat = item.alamat.replace(/, [^,]*$/, "");
        item.aksi = `<button class="editTB bg-danger"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            data-alamat="${item.alamat}"
                            onclick="cariTsLab('${item.norm}', '${item.tgl}');"><i class="fa-solid fa-file-pen"></i></button>`;
    });

    $("#antrianBelum").DataTable({
        data: belumTransaksi,
        columns: [
            { data: "aksi" },
            {
                data: "status",
                className: "text-center",
                render: function (data) {
                    var backgroundColor =
                        data === "Input Hasil Belum Lengkap"
                            ? "warning"
                            : "danger";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
            },
            { data: "tanggal" },
            { data: "layanan" },
            { data: "norm", className: "col-1" },
            { data: "nama", className: "col-2" },
            { data: "alamat", className: "col-4" },
            { data: "nama_dokter", className: "col-3" },
        ],
        paging: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        pageLength: 5,
        responsive: true,
    });
}

function antrianSudah(sudahTransakasi, tgl) {
    sudahTransakasi.forEach(function (item, index) {
        item.no = index + 1;
        item.tgl = tgl;
        item.tanggal = moment(item.created_at).format("DD-MM-YYYY");
        item.alamat = item.alamat.replace(/, [^,]*$/, "");
        item.aksi = `<button class="editTB bg-danger"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            data-alamat="${item.alamat}"
                            onclick="cariTsLab('${item.norm}', '${item.tgl}');"><i class="fa-solid fa-file-pen"></i></button>`;
    });

    $("#antrianSudah").DataTable({
        data: sudahTransakasi,
        columns: [
            { data: "aksi" },
            {
                data: "status",
                className: "text-center",
                render: function (data) {
                    var backgroundColor = "success";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
            },
            { data: "tanggal" },
            { data: "layanan" },
            { data: "norm", className: "col-1" },
            { data: "nama", className: "col-2" },
            { data: "alamat", className: "col-3" },
            { data: "nama_dokter", className: "col-3" },
        ],
        paging: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        pageLength: 5,
        responsive: true,
    });
}

$(document).ready(function () {
    setTodayDate();
    antrian();
    $("#dataTrans").on("click", ".delete", function (e) {
        e.preventDefault();
        let idLab = $(this).data("id");
        let layanan = $(this).data("layanan");
        deletLab(idLab, layanan);
    });
});
