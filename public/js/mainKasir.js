function setTodayDate() {
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);
}

function validateAndSubmit() {
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
function simpan() {
    var dataTerpilih = [];
    var norm = $("#norm").val();
    var nama = $("#nama").val();
    var alamat = $("#alamat").val();
    var jaminan = $("#layanan").val();
    var notrans = $("#notrans").val();
    var umur = $("#umur").val();
    var jk = $("#jk").val();

    if (!norm || !notrans || !umur || !jk) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");
        if (!petugas) dataKurang.push("Petugas ");
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
                    return {
                        idLayanan: id,
                        norm: norm,
                        notrans: notrans,
                    };
                })
                .get();

            dataTerpilih = dataTerpilih.filter(function (item) {
                return item !== null;
            });
        }

        console.log(dataTerpilih);
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
                Swal.fire({
                    icon: "success",
                    title: massage,
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

function tampilkanOrder(notrans, norm, tgltrans, dokter) {
    var notrans = notrans ? notrans : $("#notrans").val();
    var tgl = $("#tgltrans").val();
    $.ajax({
        url: "/api/kasir/tagihan/order",
        type: "post",
        data: { notrans: notrans, tgl: tgl },
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#dataTagihan")) {
                var table = $("#dataTagihan").DataTable();
                table.destroy();
            }

            data = response;
            console.log("🚀 ~ dataLab ~ data:", data);

            // Tambahkan kolom actions dan hitung total tarif
            let totalTarif = 0;
            data.forEach((item) => {
                item.actions = `<a type="button" class="btn btn-danger btn-sm"
                                        data-id="${item.id}"
                                        data-layanan="${item.layanan.nmLayanan}"
                                        onclick="deletLab();"><i class="fas fa-trash"></i></a>`;
                // Tambahkan tarif ke total
                totalTarif += parseFloat(item.layanan.tarif) || 0;
            });
            console.log("🚀 ~ data.forEach ~ totalTarif:", totalTarif);

            $("#tagihan").val("Rp" + totalTarif.toLocaleString("id-ID"));

            // Render DataTable
            $("#dataTagihan").DataTable({
                data: data,
                columns: [
                    { data: "actions" },
                    { data: "norm" },
                    { data: "layanan.nmLayanan" },
                    { data: "layanan.tarif" },
                ],
                order: [1, "asc"],
                scrollY: "200px",
                paging: false,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Belum ada Transaksi, Silahkan Lakukan Transaksi...!!!",
            });

            cariTagihan(norm, tgltrans);
            checkedPemeriksaan(1);
            switch (dokter) {
                case "dr. AGIL DANANJAYA, Sp.P":
                    checkedPemeriksaan(3);
                    break;
                case "dr. Cempaka Nova Intani, Sp.P, FISR., MM.":
                    checkedPemeriksaan(3);
                    break;
                case "dr. SIGIT DWIYANTO":
                    checkedPemeriksaan(4);
                    break;
                case "dr. FILLY ULFA KUSUMAWARDANI":
                    checkedPemeriksaan(4);
                    break;
            }
        },
    });
}

const bayar = document.getElementById("bayar");
const kembali = document.getElementById("kembali");
const tagihan = document.getElementById("tagihan");

// Fungsi untuk menambahkan titik pemisah ribuan
function formatNumber(value) {
    return value
        .replace(/\D/g, "") // Hapus karakter non-angka
        .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik setiap 3 digit
}

// Fungsi untuk menghapus format dan mendapatkan nilai asli
function parseNumber(value) {
    return parseFloat(value.replace(/[^\d]/g, "")) || 0;
}

// Fungsi untuk menambahkan format Rupiah
function formatRupiah(value) {
    console.log("🚀 ~ formatRupiah ~ value:", value);
    return `Rp ${formatNumber(value)}`;
}

// Event untuk memformat input saat mengetik
[tagihan, bayar].forEach((input) => {
    input.addEventListener("input", function () {
        const cursorPosition = input.selectionStart; // Simpan posisi kursor
        input.value = formatNumber(input.value); // Format angka dengan titik
        input.setSelectionRange(cursorPosition, cursorPosition); // Kembalikan posisi kursor
    });

    input.addEventListener("blur", function () {
        // Tambahkan format Rupiah saat blur
        input.value = formatRupiah(input.value.replace(/\D/g, ""));
    });

    input.addEventListener("focus", function () {
        // Hapus format Rupiah saat fokus untuk mempermudah pengeditan
        input.value = input.value.replace(/[^\d]/g, "");
    });
});

// Hitung nilai kembali secara real-time
bayar.addEventListener("input", function () {
    const bayarValue = parseNumber(bayar.value);
    const tagihanValue = parseNumber(tagihan.value);
    const kembaliValue = bayarValue - tagihanValue;

    // Format hasil perhitungan dan tampilkan
    kembali.value =
        kembaliValue >= 0 ? formatRupiah(String(kembaliValue)) : "Rp 0";
});

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

$(document).ready(function () {
    antrianKasir("kasir");
    setTodayDate();

    $("#qty").on("input", function (e) {
        hitungTotalHarga();
    });
    handlePilihSemuaClick("pilih-semua", "item");
    $("#dataTagihan").DataTable({
        scrollY: "200px",
    });
    setInterval(function () {
        antrianKasir("kasir");
    }, 150000);
    tabelPemeriksaan(itemPemeriksaan, "item", "pilih-semua");
});

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});
