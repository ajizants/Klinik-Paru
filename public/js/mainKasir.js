function setTodayDate() {
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);
    $("#tglTrans").val(today);
}

function validateAndSubmit() {
    Swal.showLoading();
    var inputsToValidate = [
        "norm",
        "notrans",
        "nama",
        "layanan",
        "alamat",
        "umur",
        // "petugas",
    ];

    var error = false;
    const hargaObat = $("#harga_2").val();
    if (hargaObat == 1) {
        Swal.fire({
            icon: "error",
            title: "Harga Obat belum diisi/salah",
        });
    } else {
        inputsToValidate.forEach(function (inputId) {
            console.log("🚀 ~ inputId:", inputId);
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
            var petugas = $("#petugas").val();
            if (!norm) dataKurang.push("No RM ");
            if (!notrans) dataKurang.push("No Transaksi ");
            if (!nama) dataKurang.push("Nama Pasien ");
            if (!jaminan) dataKurang.push("Jaminan ");
            if (!alamat) dataKurang.push("Alamat ");
            if (!umur) dataKurang.push("Umur ");
            if (!petugas) dataKurang.push("Petugas ");

            Swal.fire({
                icon: "error",
                title:
                    "Data Tidak Lengkap...!!! \n\n" +
                    dataKurang.join(", ") +
                    "Belum Diisi",
            });
        } else {
            simpan();
        }
    }
}
function simpan() {
    var dataTerpilih = [];
    var norm = $("#norm").val();
    var nama = $("#nama").val();
    var alamat = $("#alamat").val();
    var jaminan = $("#layanan").val();
    var notrans = $("#notrans").val();
    var umur = $("#umur").val();
    var jk = $("#jk").val();
    var tgltrans = $("#tgltrans").val();

    // Validasi data input
    if (!norm || !notrans || !umur || !jk) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");
        if (!umur) dataKurang.push("Umur ");
        if (!jk) dataKurang.push("Jenis Kelamin ");

        Swal.fire({
            icon: "error",
            title: "Data Tidak Lengkap!",
            text: dataKurang.join(", ") + " Belum Diisi.",
        });

        if (!norm) $("#norm").focus();
        else if (!notrans) $("#notrans").focus();
        else if (!umur) $("#umur").focus();
        else if (!jk) $("#jk").focus();

        return; // Hentikan fungsi
    }

    // Validasi checkbox pemeriksaan
    var pemeriksaan = $(".data-checkbox:checked");
    if (pemeriksaan.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Mohon pilih setidaknya satu layanan.",
        });
        return; // Hentikan fungsi
    }

    // Proses data checkbox
    dataTerpilih = pemeriksaan
        .map(function () {
            var id = $(this).attr("id");
            var qty = $("#qty_" + id).val();
            var harga = $("#harga_" + id).val();

            // Validasi harga untuk id tertentu
            if (id == 2 && harga == 1) {
                Swal.fire({
                    icon: "error",
                    title: "Harga Obat salah",
                });
                return null; // Abaikan item ini
            }

            return {
                idLayanan: id,
                norm: norm,
                notrans: notrans,
                qty: qty,
                harga: harga,
                jaminan: jaminan,
            };
        })
        .get();

    // Filter data yang null
    dataTerpilih = dataTerpilih.filter(function (item) {
        return item !== null;
    });

    if (dataTerpilih.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Tidak ada data layanan yang valid.",
        });
        return; // Hentikan fungsi
    }

    // Kirim data ke server
    fetch("/api/kasir/item/add", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            notrans: notrans,
            norm: norm,
            nama: nama,
            umur: umur,
            jk: jk,
            alamat: alamat,
            jaminan: jaminan,
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
            console.log(data);
            Swal.fire({
                icon: "success",
                title: data.message,
            });
            var notrans = $("#notrans").val();
            riwayat(notrans);
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
                title: "Terjadi masalah: " + error.message,
            });
        });
}

function deleteItemTrans(button) {
    const no = $(button).data("no");
    const id = $(button).data("id");
    const notrans = $(button).data("notrans");
    const layanan = $(button).data("layanan") || "Transaksi ini";

    console.log("🚀 ~ deleteItemTrans ~ id:", id);
    console.log("🚀 ~ deleteItemTrans ~ notrans:", notrans);

    Swal.fire({
        title: "Konfirmasi",
        text: `Apakah Anda yakin ingin menghapus transaksi: ${layanan}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/api/kasir/item/delete", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ id, notrans }),
            })
                .then((response) => {
                    if (!response.ok) {
                        console.log("Response status:", response.status);
                        return response.json().then((data) => {
                            throw new Error(data.message || "Request failed");
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Response data:", data);

                    Toast.fire({
                        icon: "success",
                        title: "Data transaksi berhasil dihapus!",
                    });

                    // Ambil referensi ke DataTable
                    const table = $("#dataTagihan").DataTable();

                    // Cari baris dengan ID tertentu
                    const row = table.row(`#row_${id}`);
                    if (row.length) {
                        row.remove().draw(false); // Hapus baris jika ditemukan
                    } else {
                        console.warn("Baris dengan id", id, "tidak ditemukan.");
                    }
                })
                .catch((error) => {
                    console.error("Fetch operation error:", error);

                    Swal.fire({
                        icon: "error",
                        title: "Terjadi masalah",
                        text: error.message,
                    });
                });
        }
    });
}

function deleteTransaksi(notrans, id, nama, norm) {
    Swal.fire({
        title: "Konfirmasi",
        text:
            "Apakah Anda yakin ingin menghapus transaksi Psien: \n" +
            norm +
            " - " +
            nama +
            "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/api/kasir/transaksi/delete", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ notrans, id }),
            })
                .then((response) => {
                    if (!response.ok) {
                        console.log("Response status:", response.status);
                        return response.json().then((data) => {
                            throw new Error(data.message || "Request failed");
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Response data:", data);

                    Toast.fire({
                        icon: "success",
                        title: "Data transaksi berhasil dihapus!",
                    });

                    resetForm();
                })
                .catch((error) => {
                    console.error("Fetch operation error:", error);

                    Swal.fire({
                        icon: "error",
                        title: "Terjadi masalah",
                        text: error.message,
                    });
                });
        }
    });
}

function tampilkanOrder(notrans, norm, tgltrans, dokter) {
    const tgl = $("#tgltrans").val();
    notrans = notrans || $("#notrans").val();
    const requestData = { notrans, tgl };

    $.ajax({
        url: "/api/kasir/tagihan/order",
        type: "post",
        data: requestData,
        success: isiTabelItemTransaksi,
        error: (xhr, status, error) =>
            handleError(norm, tgltrans, dokter, error),
    });
}

function riwayat(notrans, norm, tgltrans, dokter) {
    const tgl = $("#tgltrans").val();
    notrans = notrans || $("#notrans").val();
    const requestData = { notrans, tgl };

    $.ajax({
        url: "/api/kasir/kunjungan",
        type: "post",
        data: requestData,
        // success: isiTabelItemTransaksi,
        success: function (response) {
            isiTabelItemTransaksi(response);
            const idKunjungan = response.id;
            const notrans = response.notrans;
            const nama = response.nama;
            const norm = response.norm;
            $("#divHapus").html(
                `<button type="button" class="btn btn-danger" onclick="deleteTransaksi('${notrans}', '${idKunjungan}', '${nama}', '${norm}');">Hapus</button>`
            );
        },
        error: (xhr, status, error) =>
            handleError(norm, tgltrans, dokter, error, true),
    });
}

function isiTabelItemTransaksi(response) {
    const data = response.item || [];
    const pasien = response;
    const totalTarif = data.reduce((total, item, index) => {
        item.no = index;
        item.actions = `
            <a type="button" class="btn btn-danger btn-sm"
                data-id="${item.id}"
                data-no="${item.no}"
                data-notrans="${item.notrans}"
                data-layanan="${item.layanan?.nmLayanan || ""}"
                onclick="deleteItemTrans(this);">
                <i class="fas fa-trash"></i>
            </a>`;
        return total + (parseFloat(item.totalHarga) || 0);
    }, 0);

    $("#tagihan").val(
        `Rp${(pasien.tagihan || totalTarif).toLocaleString("id-ID")}`
    );
    $("#kembali").val(`Rp${(pasien.kembalian || 0).toLocaleString("id-ID")}`);
    $("#bayar").val(`Rp${(pasien.bayar || 0).toLocaleString("id-ID")}`);
    $("#petugas").val(pasien.petugas || "");

    if ($.fn.DataTable.isDataTable("#dataTagihan")) {
        const tableTrans = $("#dataTagihan").DataTable();
        tableTrans.clear().destroy();
    }

    $("#dataTagihan").DataTable({
        data: data,
        columns: [
            { data: "actions" },
            { data: "norm" },
            { data: "layanan.nmLayanan" },
            { data: "totalHarga" },
        ],
        createdRow: function (row, data) {
            $(row).attr("id", "row_" + data.id);
        },
        order: [1, "asc"],
        scrollY: "200px",
        paging: false,
    });
}

function handleError(
    norm,
    tgltrans,
    dokter,
    error,
    includePemeriksaan2 = false
) {
    console.error("Error:", error);

    Swal.fire({
        icon: "error",
        title: "Belum ada Transaksi, Silahkan Lakukan Transaksi...!!!",
    });

    if ($.fn.DataTable.isDataTable("#dataTagihan")) {
        const tableTrans = $("#dataTagihan").DataTable();
        tableTrans.clear().destroy();
    }

    $("#dataTagihan").DataTable({ scrollY: "200px" });
    document.getElementById("form_pembayaran").reset();
    cariTagihan(norm, tgltrans);
    // checkedPemeriksaan(1);
    if (includePemeriksaan2) {
        checkedPemeriksaan(2);
    }
    handleDokterPemeriksaan(dokter);
}

function handleDokterPemeriksaan(dokter) {
    const dokterMapping = {
        "dr. Agil Dananjaya, Sp.P": 3,
        "dr. Cempaka Nova Intani, Sp.P, FISR., MM.": 3,
        "dr. Sigit Dwiyanto": 4,
        "dr. Filly Ulfa Kusumawardani": 4,
    };

    if (dokter in dokterMapping) {
        checkedPemeriksaan(dokterMapping[dokter]);
    }
}

const bayar = document.getElementById("bayar");
const kembali = document.getElementById("kembali");
const tagihan = document.getElementById("tagihan");

// Fungsi untuk menambahkan titik pemisah ribuan
function formatNumber(value) {
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik setiap 3 digit
    // return value
    //     .replace(/\D/g, "") // Hapus karakter non-angka
    //     .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik setiap 3 digit
}
// Fungsi untuk memformat angka dengan titik setiap 3 digit
function formatNumber2(value) {
    return value
        .replace(/\D/g, "") // Hapus karakter non-angka
        .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik setiap 3 digit
}

// Fungsi untuk menghapus format dan mendapatkan nilai asli (numerik)
function parseNumber(value) {
    return parseFloat(value.replace(/[^\d]/g, "")) || 0;
}

// Fungsi untuk menambahkan format Rupiah
function formatRupiah(value) {
    const formattedNumber = formatNumber2(value.replace(/\D/g, "")); // Format angka dengan titik
    return `Rp ${formattedNumber}`; // Tambahkan "Rp " di depan angka
}

// Fungsi untuk menjaga posisi kursor setelah memformat angka
function setCursorPosition(input, oldValue, cursorPosition) {
    const diff = input.value.length - oldValue.length;
    input.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
}

// Event untuk memformat input saat mengetik
[tagihan, bayar].forEach((input) => {
    input.addEventListener("input", function () {
        const oldValue = input.value; // Simpan nilai lama
        const cursorPosition = input.selectionStart; // Simpan posisi kursor

        input.value = formatNumber2(input.value); // Format angka dengan titik
        setCursorPosition(input, oldValue, cursorPosition); // Kembalikan posisi kursor
    });

    input.addEventListener("blur", function () {
        // Tambahkan format Rupiah saat blur
        input.value = formatRupiah(input.value); // Format ke Rupiah
    });

    input.addEventListener("focus", function () {
        // Hapus format Rupiah saat fokus untuk mempermudah pengeditan
        input.value = input.value.replace(/[^\d]/g, ""); // Hanya angka
    });
});

// Hitung nilai kembali secara real-time
bayar.addEventListener("input", function () {
    const bayarValue = parseNumber(bayar.value);
    const tagihanValue = parseNumber(tagihan.value);
    const kembaliValue = bayarValue - tagihanValue;

    // Format hasil perhitungan dan tampilkan
    kembali.value =
        kembaliValue != "" ? formatRupiah(String(kembaliValue)) : "Rp 0";
    // kembali.value =
    //     kembaliValue >= 0 ? formatRupiah(String(kembaliValue)) : "Rp 0";
});
let noTransObat;
let number = 0;
function obatSajaIdentitas() {
    var today = new Date().toISOString().split("T")[0];
    //hilangkan - atau / di today
    let idTrans = today.replace(/-/g, "").replace(/\//g, "");
    number++;
    noTransObat = idTrans + number;

    $("#layanan").val("UMUM"); // Trigger change event if needed
    $("#norm").val("");
    $("#nama").val("");
    $("#alamat").val("KKPM");
    $("#tgltrans").val(today);
    $("#notrans").val(noTransObat);
}
function simpanTransaksi() {
    var norm = $("#norm").val();
    var nama = $("#nama").val();
    var alamat = $("#alamat").val();
    var jaminan = $("#layanan").val();
    var notrans = $("#notrans").val();
    var petugas = $("#petugas").val();
    var umur = $("#umur").val();
    var jk = $("#jk").val();
    var tagihan = $("#tagihan").val();
    var bayar = $("#bayar").val();
    var kembalian = $("#kembali").val();
    var tgltrans = $("#tgltrans").val();

    $.ajax({
        url: "/api/kasir/transaksi",
        type: "post",
        data: {
            norm: norm,
            nama: nama,
            alamat: alamat,
            jaminan: jaminan,
            notrans: notrans,
            petugas: petugas,
            umur: umur,
            jk: jk,
            tagihan: tagihan,
            bayar: bayar,
            kembalian: kembalian,
            tgltrans: tgltrans,
        },
        success: function (response) {
            console.log(response);
            Swal.fire({
                icon: "success",
                title: response.message,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: error,
            });
        },
    });
}

function resetForm(message) {
    document.getElementById("form_identitas").reset();
    document.getElementById("form_pembayaran").reset();
    tabelPemeriksaan(itemPemeriksaan, "item", "pilih-semua");
    $("#harga_2").val(1);

    if ($.fn.DataTable.isDataTable("#dataTagihan")) {
        let tableTrans = $("#dataTagihan").DataTable();
        tableTrans.clear().destroy();
    }
    $("#dataTagihan").DataTable({
        scrollY: "200px",
    });
    $("#divHapus").html("");
    Swal.fire({
        icon: "info",
        title: message + "\n Maturnuwun...!!!",
    });

    document.getElementById("tgltrans").value = new Date()
        .toISOString()
        .split("T")[0];
    antrianAll("kasir");

    scrollToTop();
}

$(document).ready(function () {
    antrianAll("kasir");
    setTodayDate();

    $("#qty").on("input", function (e) {
        hitungTotalHarga();
    });
    handlePilihSemuaClick("pilih-semua", "item");
    $("#dataTagihan").DataTable({
        scrollY: "200px",
    });
    setInterval(function () {
        antrianAll("kasir");
    }, 150000);
    tabelPemeriksaan(itemPemeriksaan, "item", "pilih-semua");
});

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});
