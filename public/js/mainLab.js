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
        ],
        order: [1, "asc"],
        scrollY: "220px",
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

function askLab(button) {
    var norm = $(button).data("norm");
    var nama = $(button).data("nama");
    var dokter = $(button).data("kddokter");
    var alamat = $(button).data("alamat");
    var layanan = $(button).data("layanan");
    var notrans = $(button).data("notrans");
    var tgltrans = $(button).data("tgltrans");
    var asktind = $(button).data("asktind");

    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#dokter").val(dokter);
    $("#dokter").trigger("change");
    $("#alamat").val(alamat);
    $("#layanan").val(layanan).trigger("change");
    $("#notrans").val(notrans);
    $("#tgltrans").val(tgltrans);

    // Memperbarui konten asktindContent
    $("#permintaan").html(`<b>${asktind}</b>`);

    scrollToInputSection();
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

    if (!norm || !notrans || !dokter || !petugas) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");
        if (!dokter) dataKurang.push("Dokter ");
        if (!petugas) dataKurang.push("Petugas ");

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
                var massage = data.message;
                var notrans = $("#notrans").val();
                tampilkanOrder(notrans);
                $('table thead input[type="checkbox"]').prop("checked", false);
                $('table tbody input[type="checkbox"]').prop("checked", false);
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

function tampilkanOrder(notrans) {
    console.log("🚀 ~ dataTindakan ~ notrans:", notrans);
    var notrans = notrans ? notrans : $("#notrans").val();
    console.log("🚀 ~ dataTindakan ~ notrans:", notrans);
    $.ajax({
        url: "/api/cariTsLab",
        type: "post",
        data: { notrans: notrans },
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#dataTrans")) {
                var table = $("#dataTrans").DataTable();
                table.destroy();
            }

            data = response;
            console.log("🚀 ~ dataLab ~ data:", data);
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
                    { data: "norm", className: "col-2" },
                    { data: "pemeriksaan.nmLayanan" },
                ],
                order: [1, "asc"],
                scrollY: "220px",
                paging: false,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Data tidak ditemukan...!!!",
            });
        },
    });
}
function delete_ts() {
    var notrans = $("#notrans").val();
    if (notrans) {
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menghapus transaksi ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/lab/deleteTs",
                    type: "POST",
                    data: { notrans: notrans },
                    success: function (response) {
                        resetForm("Data transaksi obat berhasil dihapus...!!!");
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Data tidak ditemukan...!!!",
                        });
                    },
                });
            }
        });
    } else {
    }
}
function deletLab(idLab, layanan) {
    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menghapus transaksi: " + layanan + " ?",
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
                    // Ambil referensi ke tabel
                    var table = $("#dataTrans").DataTable(); // Ganti dengan selector yang sesuai

                    // Cari dan hapus baris dengan idLab yang dihapus dari tabel
                    var rowIndex = table.row("#row_" + idLab).index();
                    table.row(rowIndex).remove().draw(false); // Menghapus baris dan menggambar ulang tabel

                    // Update ulang nomor urutan (no) pada semua baris yang tersisa
                    table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                        var data = this.data();
                        data.no = rowLoop + 1; // Nomor urutan dimulai dari 1

                        // Update data pada baris
                        this.data(data).draw(false);
                    });
                },
                error: function (xhr, status, error) {
                    Toast.fire({
                        icon: "error",
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
async function cariTsLab(norm, tgl, ruang) {
    resetForm();

    norm = norm || formatNorm($("#norm").val);
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
                cariKominfo(norm, tgl, ruang);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
                throw new Error("Network response was not ok");
            }
        } else {
            const data = await response.json();
            console.log("🚀 ~ cariTsLab ~ data:", data);
            $("#norm").val(data.norm);
            $("#nama").val(data.nama);
            $("#nik").val(data.nik);
            $("#alamat").val(data.alamat);
            $("#notrans").val(data.notrans);
            $("#layanan").val(data.layanan);
            $("#dokter").val(data.dokter).trigger("change");
            $("#analis").val(data.petugas).trigger("change");

            dataLab(data);
            Swal.close();
            var btndelete = document.getElementById("delete_ts");
            btndelete.style.display = "block";
            scrollToInputSection();
        }
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
        });
    }
}
function dataLab(data) {
    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        var table = $("#dataTrans").DataTable();
        table.destroy();
    }

    data = data.pemeriksaan;
    console.log("🚀 ~ dataLab ~ data:", data);
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
        ],
        order: [1, "asc"],
        scrollY: "220px",
        paging: false,
    });
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

function resetForm(message) {
    $('table thead input[type="checkbox"]').prop("checked", false);
    $('table tbody input[type="checkbox"]').prop("checked", false);
    document.getElementById("form_identitas").reset();
    document.getElementById("form_Petugas").reset();
    $("#permintaan").html("");
    $("#analis,#dokter,#tujuan").trigger("change");
    var btndelete = document.getElementById("delete_ts");
    btndelete.style.display =
        btndelete.style.display === "none" ? "block" : "none";

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        let tableTrans = $("#dataTrans").DataTable();
        tableTrans.clear().destroy();
    }
    Swal.fire({
        icon: "info",
        title: message + "\n Maturnuwun...!!!",
    });

    document.getElementById("tgltrans").value = new Date()
        .toISOString()
        .split("T")[0];
}
function batal() {
    resetForm("Transaksi Lab dibatalkan...!!!");
    scrollToTop();
}

// function processAntrianData(data, filter, tabel) {
//     $("#loadingSpinner").show();
//     var filteredData = data.filter(function (item) {
//         return item.status === filter;
//     });

//     filteredData.forEach(function (item) {
//         item.aksi = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
//                       onclick="cariTsRo('${item.pasien_no_rm}','${$(
//             "#tanggal"
//         ).val()}');rstForm();"><i class="fas fa-pen-to-square"></i></a>`;
//     });

//     if ($.fn.DataTable.isDataTable(tabel)) {
//         var table = tabel.DataTable();
//         table.clear().rows.add(filteredData).draw();
//     } else {
//         initializeAntrian(tabel, filteredData);
//     }
//     $("#loadingSpinner").hide();
// }

// function fetchDataAntrian(params, callback) {
//     console.log("🚀 ~ fetchDataAntrian ~ params:", params);
//     $.ajax({
//         url: "/api/cpptKominfo",
//         type: "post",
//         data: params,
//         success: function (response) {
//             callback(response);
//         },
//         error: function (xhr) {
//             // Tangani kesalahan jika diperlukan
//         },
//     });
// }

// // Fungsi untuk inisialisasi tabel data antrian
// function initializeDataAntrian(response) {
//     if (response && response.response && response.response.data) {
//         var dataArray = response.response.data.filter(function (item) {
//             return item.status === "belum";
//         });

//         dataArray.forEach(function (item) {
//             var asktind = "";
//             if (item.radiologi && Array.isArray(item.radiologi)) {
//                 item.radiologi.forEach(function (radiologi) {
//                     asktind += `${radiologi.layanan} (${radiologi.keterangan}), `;
//                 });
//             }
//             item.asktind = asktind;
//             item.index = dataArray.indexOf(item) + 1;

//             var alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
//             item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
//                             data-norm="${item.pasien_no_rm}"
//                             data-nama="${item.pasien_nama}"
//                             data-dokter="${item.dokter_nama}"
//                             data-asktind="${asktind}"
//                             data-kddokter="${item.nip_dokter}"
//                             data-alamat="${alamat}"
//                             data-layanan="${item.penjamin_nama}"
//                             data-notrans="${item.no_trans}"
//                             data-tgltrans="${item.tanggal}"
//                             onclick="askRo(this);"><i class="fas fa-pen-to-square"></i></a>`;
//         });

//         $("#dataAntrian").DataTable({
//             data: dataArray,
//             columns: [
//                 { data: "aksi", className: "text-center p-2" },
//                 {
//                     data: "status",
//                     className: "text-center p-2",
//                     render: function (data) {
//                         var backgroundColor =
//                             data === "belum" ? "danger" : "success";
//                         return `<div class="badge badge-${backgroundColor}">${data}</div>`;
//                     },
//                 },
//                 { data: "antrean_nomor", className: "text-center p-2" },
//                 { data: "tanggal", className: "text-center p-2 col-1" },
//                 { data: "penjamin_nama", className: "text-center p-2" },
//                 { data: "pasien_no_rm", className: "text-center p-2" },
//                 { data: "pasien_nama", className: "p-2 col-2" },
//                 { data: "asktind", className: "p-2 col-4" },
//                 { data: "dokter_nama", className: "p-2 col-2" },
//             ],
//             order: [
//                 [1, "asc"],
//                 [2, "asc"],
//             ],
//         });
//     } else {
//         console.error(
//             "Invalid response or response.response.data is not available:",
//             response
//         );
//         // Tangani error atau tampilkan pesan yang sesuai
//     }
// }

// // Fungsi untuk mengambil dan menampilkan data antrian
// function antrian() {
//     $("#loadingSpinner").show();
//     var tanggal_awal = $("#tanggal").val();
//     var tanggal_akhir = $("#tanggal").val();

//     var param = {
//         tanggal_awal: tanggal_awal,
//         tanggal_akhir: tanggal_akhir,
//         ruang: "lab",
//     };

//     fetchDataAntrian(param, function (response) {
//         $("#loadingSpinner").hide();

//         if ($.fn.DataTable.isDataTable("#dataAntrian")) {
//             var table = $("#dataAntrian").DataTable();
//             if (response && response.response && response.response.data) {
//                 var dataArray = response.response.data.filter(function (item) {
//                     return item.status === "belum";
//                 });

//                 // Proses ulang data untuk memperbarui kolom 'aksi' dan lainnya jika diperlukan
//                 dataArray.forEach(function (item) {
//                     var asktind = "";
//                     if (item.radiologi && Array.isArray(item.radiologi)) {
//                         item.radiologi.forEach(function (radiologi) {
//                             asktind += `${radiologi.layanan} ket: ${radiologi.layanan}, `;
//                         });
//                     }
//                     item.asktind = asktind;
//                     item.index = dataArray.indexOf(item) + 1;

//                     var alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
//                     item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
//                                     data-norm="${item.pasien_no_rm}"
//                                     data-nama="${item.pasien_nama}"
//                                     data-dokter="${item.dokter_nama}"
//                                     data-asktind="${asktind}"
//                                     data-kddokter="${item.nip_dokter}"
//                                     data-alamat="${alamat}"
//                                     data-layanan="${item.penjamin_nama}"
//                                     data-notrans="${item.no_trans}"
//                                     data-tgltrans="${item.tanggal}"
//                                     onclick="askRo(this);"><i class="fas fa-pen-to-square"></i></a>`;
//                 });

//                 // Hapus data yang ada, tambahkan data baru, dan gambar ulang tabel
//                 table.clear().rows.add(dataArray).draw();
//             } else {
//                 console.error(
//                     "Invalid response or response.response.data is not available:",
//                     response
//                 );
//             }
//         } else {
//             initializeDataAntrian(response);
//         }
//     });
// }

function updateAntrian() {
    antrian("lab");
    antrianAll("lab");
}

$(document).ready(function () {
    setTodayDate();
    // handlePilihSemuaClick("pilih-hematologi", "hematologi");
    // handlePilihSemuaClick("pilih-kimia", "kimia");
    // handlePilihSemuaClick("pilih-imuno", "imuno");
    handlePilihSemuaClick("pilih-bakteriologi", "bakteriologi");
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
    // layanan(91, "hematologi", "pilih-hematologi");
    // layanan(92, "kimia", "pilih-kimia");
    // layanan(93, "imuno", "pilih-imuno");
    layanan(9, "bakteriologi", "pilih-bakteriologi");

    // $("#dataAntrian").on("click", ".panggil", function (e) {
    //     e.preventDefault();

    //     let panggilData = $(this).data("panggil");
    //     console.log(
    //         "🚀 ~ file: mainFarmasi.js:478 ~ panggilData:",
    //         panggilData
    //     );

    //     panggilPasien(panggilData);
    // });

    $("#dataTrans").on("click", ".delete", function (e) {
        e.preventDefault();
        let idLab = $(this).data("id");
        let layanan = $(this).data("layanan");
        deletLab(idLab, layanan);
    });
});
