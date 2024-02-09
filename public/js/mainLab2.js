var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

let table; // Declare the DataTable variable outside the function

function layanan(kelas, grupLayanan, pilihSemuaId) {
    if ($.fn.DataTable.isDataTable("#" + grupLayanan)) {
        table.clear().destroy();
    }

    table = $("#" + grupLayanan).DataTable({
        ajax: {
            url: "/api/layananlab",
            type: "POST",
            dataType: "json",
            dataSrc: "data",
            data: { kelas: kelas },
        },
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="select-checkbox mt-2 data-checkbox ${grupLayanan}" id="${row.idLayanan}">`;
                },
            },
            {
                data: "nmLayanan",
                render: function (data, type, row) {
                    return `<label type="text" class="form-check-label mt-1" for="${row.idLayanan}" style="font-size: medium;">${data}</label>`;
                },
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="text" class="form-control-sm col-6" id="ket${row.idLayanan}">`;
                },
            },
            {
                data: "tarif",
                render: function (data, type, row) {
                    var formattedTarif = parseInt(data).toLocaleString(
                        "id-ID",
                        {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        }
                    );
                    return `<label type="text" class="form-check-label mt-1" for="${row.idLayanan}" style="font-size: medium;">${formattedTarif}</label>`;
                },
            },
            // {
            //     data: null,
            //     render: function (data, type, row) {
            //         return `<input type="checkbox" class="select-checkbox mt-2 data-checkbox ${grupLayanan}" id="biaya${row.idLayanan}">`;
            //     },
            // },
            // {
            //     data: null,
            //     render: function (data, type, row) {
            //         return `<input type="text" class="form-control-sm col-6" id="tagihan${row.idLayanan}">`;
            //     },
            // },
        ],
        order: [1, "asc"],
        scrollY: "200px",
        scrollCollapse: false,
        paging: false,
        responsive: true,
    });
}

function handlePilihSemuaClick(pilihSemuaId, checkboxClass) {
    const pilihSemuaCheckbox = document.getElementById(pilihSemuaId);

    pilihSemuaCheckbox.addEventListener("change", function () {
        const isChecked = this.checked;
        const checkboxes = $("." + checkboxClass);

        checkboxes.prop("checked", isChecked);
    });
}

async function dataLab() {
    var notrans = $("#notrans").val();

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        var table = $("#dataTrans").DataTable();
        table.destroy();
    }

    try {
        const response = await fetch("/api/cariLaboratorium", {
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

        data.forEach((item, index) => {
            item.actions = `<a href="" class="edit"
                                data-id="${item.IdLab}"
                                data-norm="${item.norm}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>
                            <a class="delete"
                                data-id="${item.IdLab}"
                                data-layanan="${item.NamaPemeriksaan}"
                                onclick="deletLab();"><i class="fas fa-trash"></i></a>`;
            item.no = index + 1;
        });

        $("#dataTrans").DataTable({
            data: data,
            columns: [
                { data: "actions", className: "px-0 col-1 text-center" },
                { data: "no" },
                { data: "NORM" },
                { data: "NamaPemeriksaan" },
                { data: "Ket" },
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
    var petugas = $("#analis").val();
    var dokter = $("#dokter").val();
    var tujuan = $("#tujuan").val();

    if (!norm || !notrans || !petugas || !dokter || !tujuan) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");
        if (!petugas) dataKurang.push("Petugas ");
        if (!dokter) dataKurang.push("Dokter ");
        if (!tujuan) dataKurang.push("Tujuan ");

        Swal.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! " +
                dataKurang.join(", ") +
                "Belum Diisi",
        });
    } else {
        var pemeriksaan = $(".data-checkbox:checked");

        if (pemeriksaan.length === 0) {
            Swal.fire({
                icon: "error",
                title: "Mohon pilih setidaknya satu layanan.",
            });
        } else {
            dataTerpilih = pemeriksaan
                .map(function () {
                    var id = $(this).attr("id");
                    var ket = $("#ket" + id).val();

                    return {
                        idLayanan: id,
                        norm: norm,
                        notrans: notrans,
                        ket: ket,
                        petugas: petugas,
                        dokter: dokter,
                    };
                })
                .get();

            dataTerpilih = dataTerpilih.filter(function (item) {
                return item !== null;
            });
        }

        console.log(dataTerpilih);
        fetch("/api/addTransaksiLab", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                notrans: notrans,
                tujuan: tujuan,
                dataTerpilih: dataTerpilih,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    console.log("Response status:", response.status);
                    console.log("Response status text:", response.statusText);
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
function deletLab(idLab, layanan) {
    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menghapus transaksi" + layanan + " ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/deleteLab",
                type: "POST",
                data: { idLab: idLab },
                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data transaksi obat berhasil dihapus...!!!",
                    });
                    dataLab();
                },
                error: function (xhr, status, error) {
                    Toast.fire({
                        icon: "success",
                        title: error + "...!!!",
                    });
                },
            });
        } else {
            // Logika jika pembatalan (cancel)
            console.log("Penghapusan dibatalkan.");
        }
    });
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
    handlePilihSemuaClick("pilih-hematologi", "hematologi");
    handlePilihSemuaClick("pilih-kimia", "kimia");
    handlePilihSemuaClick("pilih-imuno", "imuno");
    handlePilihSemuaClick("pilih-bakteriologi", "bakteriologi");
    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            formatNorm($("#norm"));
            searchByRM($("#norm").val());
        }
    });
    $("#tabelData,#dataTrans").DataTable({
        scrollY: "200px",
    });
    populateDokterOptions();
    populateAnalisOptions();
    populateTujuan();
    showTunggu();
    antrian();
    antrianAll("5");
    setInterval(function () {
        antrianAll("5");
        antrian();
    }, 150000);
    layanan(91, "hematologi", "pilih-hematologi");
    layanan(92, "kimia", "pilih-kimia");
    layanan(93, "imuno", "pilih-imuno");
    layanan(94, "bakteriologi", "pilih-bakteriologi");
    $("#tanggal").on("change", antrian);

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
