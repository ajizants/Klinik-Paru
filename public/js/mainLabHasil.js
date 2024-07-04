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

            const notrans = data.notrans;
            console.log("🚀 ~ cariTsLab ~ notrans:", notrans);
            // setTimeout(function () {
            dataLab(notrans); // Panggil dataLab dengan notrans yang benar
            // }, 3000);
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
async function dataLab(notrans) {
    if ($.fn.DataTable.isDataTable("#inputHasil")) {
        var table = $("#inputHasil").DataTable();
        table.destroy();
    }

    try {
        const response = await fetch("/api/cariTsLab", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ notrans: notrans }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

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
            item.actions = `<a class="delete"
                                data-id="${item.IdLab}"
                                data-layanan="${item.NamaPemeriksaan}"
                                onclick="deletLab();"><i class="fas fa-trash"></i></a>`;
            item.no = index + 1;
        });

        $("#inputHasil").DataTable({
            data: data,
            columns: [
                { data: "actions", className: "px-0 col-1 text-center" },
                {
                    data: "no",
                },

                { data: "norm" },
                {
                    data: "pemeriksaan.nmLayanan",
                    render: function (data, type, row) {
                        return `<p type="text" class="form-control-sm col-6 hasil" id="layanan${row.IdLayanan}" value="${row.IdLayanan}" readonly>${data}</p>`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var inputId = "analis" + row.IdLab;
                        var inputField = `<select id="${inputId}" class="form-control-sm col-6 analis">`;
                        inputField += `<option value="">--- Pilih Petugas ---</option>`;
                        analisData.forEach(function (petugas) {
                            inputField += `<option value="${petugas.nip}">${petugas.gelar_d} ${petugas.nama} ${petugas.gelar_b}</option>`;
                        });
                        inputField += "</select>";
                        return inputField;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<input type="text" class="form-control-sm col-6 hasil" id="hasil${row.IdLab}">`;
                    },
                },
            ],
            order: [1, "asc"],
            scrollY: "320px",
            scrollCollapse: true,
            paging: false,
        });
    } catch (error) {
        console.error("Error:", error.message);
    }
}

function simpan() {
    var dataTerpilih = [];
    var norm = $("#norm").val();
    var notrans = $("#notrans").val();

    if (!norm || !notrans) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");

        Swal.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! " +
                dataKurang.join(", ") +
                "Belum Diisi",
        });
    } else {
        var table = $("#inputHasil").DataTable();
        var dataRows = table.rows().data();

        dataRows.each(function (row, index) {
            var idLab = row.IdLab;
            var idLayanan = row.IdLayanan;
            var hasil = $("#hasil" + idLab).val();
            var petugas = $("#analis" + idLab).val();

            var rowData = {
                idLab: idLab,
                idLayanan: idLayanan,
                norm: norm,
                notrans: notrans,
                hasil: hasil,
                petugas: petugas,
            };

            dataTerpilih.push(rowData);
        });

        console.log(dataTerpilih);

        fetch("/api/addHasilLab", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ dataTerpilih: dataTerpilih }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log(data);
                Swal.fire({
                    icon: "success",
                    title: "Data berhasil tersimpan...!!!",
                });
                dataLab();
                antrian();

                // toggleInputReadonly(false);
            })
            .catch((error) => {
                console.error(
                    "There has been a problem with your fetch operation:",
                    error
                );
                Swal.fire({
                    icon: "error",
                    title:
                        "There has been a problem with your fetch operation:" +
                        error,
                });
            });
    }
}

function resetForm(message) {
    $('table thead input[type="checkbox"]').prop("checked", false);
    $('table tbody input[type="checkbox"]').prop("checked", false);
    document.getElementById("frmident").reset();
    document.getElementById("frmPetugas").reset();
    $("#analis,#dokter,#tujuan").trigger("change");

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        let tableTrans = $("#dataTrans").DataTable();
        tableTrans.clear().destroy();
    }
    Swal.fire({
        icon: "info",
        title: "Transaksi " + message + " maturnuwun...!!!",
    });
    scrollToTop();
}

$(document).ready(function () {
    setTodayDate();

    $("#tabelData,#dataTrans").DataTable({
        scrollY: "200px",
    });
    populateDokterOptions();
    populateAnalisHasil();

    $("#dataAntrian").on("click", ".aksi-button", function (e) {
        e.preventDefault();
        $("#add").show();
        $("#edit").hide();
        var norm = $(this).data("norm");
        var nama = $(this).data("nama");
        var nik = $(this).data("nik");
        var dokter = $(this).data("kddokter");
        var alamat = $(this).data("alamat");
        var layanan = $(this).data("layanan");
        var notrans = $(this).data("notrans");
        var tgltrans = $(this).data("tgltrans");

        $("#norm").val(norm);
        $("#nik").val(nik);
        $("#nama").val(nama);
        $("#dokter").val(dokter).trigger("change");
        $("#apoteker").val("197609262011012003").trigger("change");
        $("#alamat").val(alamat);
        $("#layanan").val(layanan);
        $("#notrans").val(notrans);
        $("#tgltrans").val(tgltrans);

        scrollToInputSection();
        dataLab();
    });

    $("#dataAntrian").on("click", ".panggil", function (e) {
        e.preventDefault();

        let panggilData = $(this).data("panggil");
        console.log(
            "🚀 ~ file: mainFarmasi.js:478 ~ panggilData:",
            panggilData
        );

        panggilPasien(panggilData);
    });
    $("#dataTrans").on("click", ".delete", function (e) {
        e.preventDefault();
        let idLab = $(this).data("id");
        let layanan = $(this).data("layanan");
        deletLab(idLab, layanan);
    });
});
