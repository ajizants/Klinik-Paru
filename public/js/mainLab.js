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
                    return `<input type="text" class="form-control-sm col-6" id="hasil${row.idLayanan}">`;
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
        // responsive: true,
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

function simpan() {
    var dataTerpilih = [];
    var norm = $("#norm").val();
    var nama = $("#nama").val();
    var alamat = $("#alamat").val();
    var nik = $("#nik").val();
    var jaminan = $("#layanan").val();
    var notrans = $("#notrans").val();
    var petugas = $("#analis").val();
    var dokter = $("#dokter").val();
    var tujuan = $("#tujuan").val();

    if (!norm || !notrans || !dokter) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");
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
                    var hasil = $("#hasil" + id).val();

                    return {
                        idLayanan: id,
                        norm: norm,
                        notrans: notrans,
                        hasil: hasil,
                        // petugas: petugas,
                        // dokter: dokter,
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
                norm: norm,
                nama: nama,
                nik: nik,
                alamat: alamat,
                jaminan: jaminan,
                tujuan: tujuan,
                // hasil: hasil,
                petugas: petugas,
                dokter: dokter,
                dataTerpilih: dataTerpilih,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    console.log("Response status:", response.status);
                    console.log("Response status text:", response.message);
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
                if ($.fn.DataTable.isDataTable("#dataTrans")) {
                    var table = $("#dataTrans").DataTable();
                    table.destroy();
                }
                dataLab(notrans);
                updateAntrian();

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
async function cariTsLab(norm, tgl) {
    resetForm();
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
            ckelisPemeriksaan(data);
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

function ckelisPemeriksaan(data) {
    if (data.pemeriksaan && Array.isArray(data.pemeriksaan)) {
        data.pemeriksaan.forEach((item) => {
            const checkbox = document.getElementById(`${item.idLayanan}`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    } else {
        console.error("Data pemeriksaan tidak ditemukan atau bukan array.");
    }
}

async function dataLab(notrans) {
    console.log("🚀 ~ dataLab ~ notrans:", notrans);
    notrans = notrans || document.getElementById("notrans").value;
    console.log("🚀 ~ dataLab ~ notrans (setelah assignment):", notrans);

    // Tambahkan logika fetch atau AJAX untuk mendapatkan data lab berdasarkan notrans di sini
    if (!notrans) {
        console.error("Nilai notrans kosong!");
        return;
    }

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        var table = $("#dataTrans").DataTable();
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

        data.forEach((item, index) => {
            item.actions = `<a class="delete"
                                data-id="${item.idLab}"
                                data-layanan="${item.pemeriksaan.nmLayanan}"
                                onclick="deletLab();"><i class="fas fa-trash"></i></a>`;
            item.no = index + 1;
        });

        $("#dataTrans").DataTable({
            data: data,
            columns: [
                { data: "actions", className: "px-0 col-1 text-center" },
                { data: "no" },
                { data: "norm" },
                { data: "pemeriksaan.nmLayanan" },
                { data: "hasil" },
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

function resetForm(message) {
    $('table thead input[type="checkbox"]').prop("checked", false);
    $('table tbody input[type="checkbox"]').prop("checked", false);
    document.getElementById("form_identitas").reset();
    document.getElementById("form_Petugas").reset();
    $("#analis,#dokter,#tujuan").trigger("change");

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        let tableTrans = $("#dataTrans").DataTable();
        tableTrans.clear().destroy();
    }
    Swal.fire({
        icon: "info",
        title: "Transaksi Selesai maturnuwun...!!!",
    });
    scrollToTop();
}

function updateAntrian() {
    // antrian();

    antrianAll("lab");
}

$(document).ready(function () {
    setTodayDate();
    handlePilihSemuaClick("pilih-hematologi", "hematologi");
    handlePilihSemuaClick("pilih-kimia", "kimia");
    handlePilihSemuaClick("pilih-imuno", "imuno");
    handlePilihSemuaClick("pilih-bakteriologi", "bakteriologi");
    // $("#norm").on("keyup", function (event) {
    //     if (event.key === "Enter") {
    //         event.preventDefault();
    //         formatNorm($("#norm"));
    //         searchByRM($("#norm").val());
    //     }
    // });
    $("#tabelData,#dataTrans").DataTable({
        scrollY: "200px",
    });
    populateDokterOptions();
    populateAnalisOptions();
    populateTujuan();
    updateAntrian();
    setInterval(function () {
        updateAntrian();
    }, 150000);
    layanan(91, "hematologi", "pilih-hematologi");
    layanan(92, "kimia", "pilih-kimia");
    layanan(93, "imuno", "pilih-imuno");
    layanan(94, "bakteriologi", "pilih-bakteriologi");

    // $("#dataAntrian").on("click", ".aksi-button", function (e) {
    //     e.preventDefault();
    //     $("#add").show();
    //     $("#edit").hide();
    //     var norm = $(this).data("norm");
    //     var nama = $(this).data("nama");
    //     var nik = $(this).data("nik");
    //     var dokter = $(this).data("kddokter");
    //     var alamat = $(this).data("alamat");
    //     var layanan = $(this).data("layanan");
    //     var notrans = $(this).data("notrans");
    //     var tgltrans = $(this).data("tgltrans");

    //     $("#norm").val(norm);
    //     $("#nik").val(nik);
    //     $("#nama").val(nama);
    //     $("#dokter").val(dokter).trigger("change");
    //     $("#apoteker").val("197609262011012003").trigger("change");
    //     $("#alamat").val(alamat);
    //     $("#layanan").val(layanan);
    //     $("#notrans").val(notrans);
    //     $("#tgltrans").val(tgltrans);

    //     scrollToInputSection();
    //     dataLab();
    // });

    // $("#dataAntrian").on("click", ".panggil", function (e) {
    //     e.preventDefault();

    //     let panggilData = $(this).data("panggil");
    //     console.log(
    //         "🚀 ~ file: mainFarmasi.js:478 ~ panggilData:",
    //         panggilData
    //     );

    //     panggilPasien(panggilData);
    // });

    // $("#antrianall").on("click", ".aksi-button", function (e) {
    //     e.preventDefault();
    //     $("#add").show();
    //     $("#edit").hide();
    //     var norm = $(this).data("norm");
    //     console.log("🚀 ~ inputData ~ norm:", norm);
    //     var nama = $(this).data("nama");
    //     var nik = $(this).data("nik");
    //     var dokter = $(this).data("kddokter");
    //     var alamat = $(this).data("alamat");
    //     var layanan = $(this).data("layanan");
    //     var notrans = $(this).data("notrans");
    //     var tgltrans = $(this).data("tgltrans");

    //     $("#norm").val(norm);
    //     $("#nik").val(nik);
    //     $("#nama").val(nama);
    //     $("#dokter").val(dokter).trigger("change");
    //     $("#apoteker").val("197609262011012003").trigger("change");
    //     $("#alamat").val(alamat);
    //     $("#layanan").val(layanan);
    //     $("#notrans").val(notrans);
    //     $("#tgltrans").val(tgltrans);

    //     scrollToInputSection();
    //     dataLab();
    // });
    $("#dataTrans").on("click", ".delete", function (e) {
        e.preventDefault();
        let idLab = $(this).data("id");
        let layanan = $(this).data("layanan");
        deletLab(idLab, layanan);
    });
});
