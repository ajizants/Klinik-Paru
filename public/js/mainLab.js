var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

let table; // Declare the DataTable variable outside the function
function tabelPemeriksaan(itemPemeriksaan, item, pilihSemuaId) {
    // console.log("🚀 ~ itemPemeriksaan ~ itemPemeriksaan:", itemPemeriksaan);
    if ($.fn.DataTable.isDataTable("#tabelPemeriksaan")) {
        table.clear().destroy();
    }

    table = $("#tabelPemeriksaan").DataTable({
        data: itemPemeriksaan,
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="select-checkbox mt-2 data-checkbox ${item}" id="${row.idLayanan}">`;
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
    var umur = $(button).data("umur");
    let jk = $(button).data("jk");
    var tujuan = $(button).data("tujuan");

    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#dokter").val(dokter);
    $("#dokter").trigger("change");
    $("#alamat").val(alamat);
    $("#layanan").val(layanan).trigger("change");
    $("#notrans").val(notrans);
    $("#tgltrans").val(tgltrans);
    $("#umur").val(umur);
    $("#jk").val(jk).trigger("change");

    // Memperbarui konten asktindContent
    $("#permintaan").html(`<b>${asktind}</b>
    <br>
    <br>
    <br>    
    <br>    
    <br>    
    <div class="font-weight-bold bg-warning rounded">${tujuan}</div>`);
    getNoSampel();
    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        let tableTrans = $("#dataTrans").DataTable();
        tableTrans.clear().destroy();
    }
    scrollToInputSection();
}
function validateAndSubmit() {
    var inputsToValidate = [
        "norm",
        "notrans",
        "nama",
        "layanan",
        "alamat",
        "umur",
        "analis",
        "dokter",
        "no_sampel",
    ];

    var error = false;

    inputsToValidate.forEach(function (inputId) {
        var inputElement = document.getElementById(inputId);
        var inputValue = inputElement.value.trim();

        if (inputValue === "") {
            if ($(inputElement).hasClass("select2-hidden-accessible")) {
                // Select2 element
                $(inputElement)
                    .next(".select2-container")
                    .addClass("input-error");
            } else {
                // Regular input element
                inputElement.classList.add("input-error");
            }
            error = true;
        } else {
            if ($(inputElement).hasClass("select2-hidden-accessible")) {
                // Select2 element
                $(inputElement)
                    .next(".select2-container")
                    .removeClass("input-error");
            } else {
                // Regular input element
                inputElement.classList.remove("input-error");
            }
        }
    });
    if (error) {
        var dataKurang = [];
        var norm = $("#norm").val();
        var notrans = $("#notrans").val();
        var nama = $("#nama").val();
        var jaminan = $("#layanan").val();
        var alamat = $("#alamat").val();
        var umur = $("#umur").val();
        var petugas = $("#analis").val();
        var dokter = $("#dokter").val();
        var noSampel = $("#no_sampel").val();
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("No Transaksi ");
        if (!nama) dataKurang.push("Nama Pasien ");
        if (!jaminan) dataKurang.push("Jaminan ");
        if (!alamat) dataKurang.push("Alamat ");
        if (!umur) dataKurang.push("Umur ");
        if (!petugas) dataKurang.push("Petugas ");
        if (!dokter) dataKurang.push("Dokter ");
        if (!noSampel) dataKurang.push("No Sampel ");

        Swal.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! \n\n" +
                dataKurang.join(", ") +
                "Belum Diisi",
        });
    } else {
        // Lakukan pengiriman data atau proses selanjutnya jika semua data valid
        simpan(); // Contoh: Panggil fungsi simpan() jika semua data valid
    }
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
    var tgltrans = $("#tgltrans").val();
    var noSampel = $("#no_sampel").val();
    var umur = $("#umur").val();
    var jk = $("#jk").val();

    if (!norm || !notrans || !dokter || !petugas || !noSampel || !umur || !jk) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");
        if (!dokter) dataKurang.push("Dokter ");
        if (!petugas) dataKurang.push("Petugas ");
        if (!noSampel) dataKurang.push("No Sampel ");
        if (!umur) dataKurang.push("Umur ");

        Swal.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! " +
                dataKurang.join(", ") +
                "Belum Diisi",
        });
        if (!norm) $("#norm").focus();
        if (!notrans) $("#notrans").focus();
        if (!dokter) $("#dokter").focus();
        if (!petugas) $("#petugas").focus();
        if (!noSampel) $("#no_sampel").focus();
        if (!umur) $("#umur").focus();
        if (!jk) $("#jk").focus();
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

        if ($("#160").is(":checked")) {
            // alert("160");
            dataTerpilih.push(
                {
                    idLayanan: 233,
                    norm: norm,
                    notrans: notrans,
                    hasil: "",
                },
                {
                    idLayanan: 234,
                    norm: norm,
                    notrans: notrans,
                    hasil: "",
                },
                {
                    idLayanan: 235,
                    norm: norm,
                    notrans: notrans,
                    hasil: "",
                },
                {
                    idLayanan: 236,
                    norm: norm,
                    notrans: notrans,
                    hasil: "",
                }
            );
        }

        // console.log(dataTerpilih);
        // return;
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
                umur: umur,
                jk: jk,
                noSampel: noSampel,
                alamat: alamat,
                jaminan: jaminan,
                tujuan: tujuan,
                petugas: petugas,
                dokter: dokter,
                tgltrans: tgltrans,
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
                // console.log(data);
                var massage = data.message;
                Swal.fire({
                    icon: "success",
                    title: massage,
                    allowOutsideClick: false,
                });
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
    // console.log("🚀 ~ dataTindakan ~ notrans:", notrans);
    var notrans = notrans ? notrans : $("#notrans").val();
    // console.log("🚀 ~ dataTindakan ~ notrans:", notrans);
    var tgl = $("#tgltrans").val();
    // console.log("🚀 ~ tampilkanOrder ~ tgl:", tgl);
    $.ajax({
        url: "/api/cariTsLab",
        type: "post",
        data: { notrans: notrans, tgl: tgl },
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#dataTrans")) {
                var table = $("#dataTrans").DataTable();
                table.destroy();
            }

            data = response;
            // console.log("🚀 ~ dataLab ~ data:", data);
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
                    {
                        data: "created_at",
                        render: function (data) {
                            // Format the date using JavaScript
                            const formattedDate = new Date(data).toLocaleString(
                                "id-ID",
                                {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                }
                            );
                            return formattedDate;
                        },
                    },
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
    resetForm("trans");

    norm = norm || formatNorm($("#norm").val);
    tgl = tgl || $("#tanggal").val();
    var requestData = { norm: norm, tgl: tgl };
    const notrans = $("#notrans").val();

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
                // cariKominfo(norm, tgl, ruang);
                $("#dataTrans").DataTable({
                    data: [], // Data kosong
                    columns: [
                        { title: "Aksi" },
                        { title: "No" },
                        { title: "No RM" },
                        { title: "Item Pemeriksaan" },
                        { title: "TGL" },
                    ],
                    language: {
                        emptyTable: "Belum Ada Transaksi", // Pesan saat tidak ada data
                    },
                    ordering: false,
                    initComplete: function () {
                        // Menambahkan CSS kustom untuk memberi background kuning
                        $("#dataTrans_wrapper .dataTables_empty").css({
                            "background-color": "yellow",
                            color: "black", // Mengubah warna teks agar tetap terlihat
                            "font-weight": "bold",
                            "text-align": "center",
                            padding: "20px",
                        });
                    },
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
                throw new Error("Network response was not ok");
            }
            Swal.close();

            cetakPermintaan(notrans, tgl, norm);
        } else {
            const data = await response.json();
            let noSampel = data.no_sampel;
            // console.log("🚀 ~ cariTsLab ~ noSampel:", noSampel);

            if (noSampel == null || noSampel == "") {
                getNoSampel();
            } else {
                $("#no_sampel").val(noSampel);
            }

            // console.log("🚀 ~ cariTsLab ~ data:", data);
            $("#norm").val(data.norm);
            $("#nama").val(data.nama);
            $("#nik").val(data.nik);
            $("#alamat").val(data.alamat);
            $("#notrans").val(data.notrans);
            $("#layanan").val(data.layanan);
            $("#dokter").val(data.dokter).trigger("change");
            $("#analis").val(data.petugas).trigger("change");

            dataLab(data, tgl);
            Swal.close();
            var btndelete = document.getElementById("delete_ts");
            btndelete.style.display = "block";
            scrollToInputSection();
            cetakPermintaan(notrans, tgl, norm);
        }
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
        });
    }
}
async function getNoSampel() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    // if (!$("#no_sampel").val()) {
    try {
        // Metode POST untuk fetch data
        const response = await fetch("/api/getNoSampel", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
        });

        // Parsing hasil response ke JSON
        const data = await response.json();
        let noSampel = data.noSample; // Sesuaikan nama properti dengan hasil fetch
        $("#no_sampel").val(noSampel);

        // Swal.close();
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mencari data...!!!",
        });
    }
    // } else {
    //     Swal.close();
    // }
}

function dataLab(data, tgl) {
    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        var table = $("#dataTrans").DataTable();
        table.destroy();
    }

    data = data.pemeriksaan;
    // console.log("🚀 ~ dataLab ~ data:", data);
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
            {
                data: "created_at",
                render: function (data) {
                    // Format the date using JavaScript
                    const formattedDate = new Date(data).toLocaleString(
                        "id-ID",
                        {
                            year: "numeric",
                            month: "numeric",
                            day: "numeric",
                        }
                    );
                    return formattedDate;
                },
            },
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
    const notrans = document.getElementById("notrans").value;
    const tglTrans = document.getElementById("tgltrans").value;
    const norm = document.getElementById("norm").value;
    // console.log("🚀 ~ resetForm ~ notrans:", notrans);
    if (message != "trans") {
        antrian("lab");
    } else {
        message = "";
    }
    // console.log(msgSelesai);
    // Swal.fire({
    //     icon: "question",
    //     title: "Apakah anda ingin mencetak form permintaan?",
    //     showCancelButton: true,
    //     confirmButtonText: "Ya",
    //     cancelButtonText: "Batal",
    //     allowOutsideClick: false,
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         cetakPermintaan(notrans, tglTrans, norm);
    //         Swal.fire({
    //             icon: "question",
    //             title: message + "\n Maturnuwun...!!!",
    //             showCancelButton: true,
    //             confirmButtonText: "Ya",
    //             cancelButtonText: "Batal",
    //             allowOutsideClick: false,
    //         });
    //     } else {
    //         Swal.fire({
    //             icon: "info",
    //             title: msgSelesai,
    //             allowOutsideClick: false,
    //         });
    //     }
    // });

    // console.log(msgSelesai);
    // Toast.fire({
    //     icon: "success",
    //     title: message + "\n Maturnuwun...!!!",
    // });
    Swal.fire({
        icon: "info",
        title: message + "\n Maturnuwun...!!!",
        allowOutsideClick: true,
    });

    $('table thead input[type="checkbox"]').prop("checked", false);
    $('table tbody input[type="checkbox"]').prop("checked", false);
    document.getElementById("form_identitas").reset();
    document.getElementById("form_Petugas").reset();
    $("#permintaan").html("");
    $("#tujuanLain").html("");
    $("#analis,#dokter,#tujuan").trigger("change");
    var btndelete = document.getElementById("delete_ts");
    btndelete.style.display =
        btndelete.style.display === "none" ? "block" : "none";

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        let tableTrans = $("#dataTrans").DataTable();
        tableTrans.clear().destroy();
    }

    document.getElementById("tgltrans").value = new Date()
        .toISOString()
        .split("T")[0];
}

function cetakPermintaan(notrans, tglTrans, norm) {
    Swal.fire({
        icon: "question",
        title: "Apakah anda ingin mencetak form permintaan?",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            notrans = notrans ? notrans : $("#notrans").val();
            norm = norm ? norm : $("#norm").val();
            tglTrans = tglTrans ? tglTrans : $("#tgltrans").val();
            const baseUrl = window.location.origin;
            const url =
                baseUrl +
                "/api/lab/cetakPermintaan/" +
                notrans +
                "/" +
                norm +
                "/" +
                tglTrans;
            window.open(url, "_blank", "noopener noreferrer");
        }
    });
}
function batal() {
    resetForm("Transaksi Lab dibatalkan...!!!");
    scrollToTop();
}

function updateAntrian() {
    antrian("lab");
    antrianAll("lab");
}

$(document).ready(function () {
    setTodayDate();
    handlePilihSemuaClick("pilih-semua", "item");
    $("#tabelData,#dataTrans").DataTable({
        scrollY: "200px",
    });
    populateTujuan();
    updateAntrian();
    setInterval(function () {
        updateAntrian();
    }, 150000);
    // layanan(9, "bakteriologi", "pilih-bakteriologi");
    tabelPemeriksaan(itemPemeriksaan, "item", "pilih-semua");

    $("#dataTrans").on("click", ".delete", function (e) {
        e.preventDefault();
        let idLab = $(this).data("id");
        let layanan = $(this).data("layanan");
        deletLab(idLab, layanan);
    });
});
