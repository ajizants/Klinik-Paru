function tagihan() {
    var totalTagihan = 0;
    var totalTagihanFarmasi = 0;
    let totalTagihanIGD = 0;
    $("#dataFarmasi .totalHarga:not(.no-total)").each(function () {
        var totalKolom = $(this).text().replace(/,/g, "").trim();
        if (!isNaN(totalKolom)) {
            totalTagihanFarmasi += parseInt(totalKolom);
        }
    });
    $("#dataIGD .totalHarga:not(.no-total)").each(function () {
        var totaligd = $(this).text().replace(/,/g, "").trim();
        if (!isNaN(totaligd)) {
            totalTagihanIGD += parseInt(totaligd);
        }
    });
    // console.log(totalTagihanFarmasi);
    // console.log(totalTagihanIGD);

    totalTagihan = totalTagihanFarmasi + totalTagihanIGD;
    // Menampilkan total pada input tagihan
    $("#tagihan").val(totalTagihan.toLocaleString());
}

function hitungTotalHarga(jml) {
    console.log("🚀 ~ hitungTotalHarga ~ jml:", jml);
    var hargaJual = parseFloat($("#jual").val()) || 0;
    var qty = parseFloat(jml) || 0;
    var totalharga = hargaJual * qty;
    $("#total").val(totalharga);
}

function loadFarmasi() {
    if ($.fn.DataTable.isDataTable("#farmasiObat")) {
        var tabelFarmasi = $("#farmasiObat").DataTable();
        tabelFarmasi.clear().destroy();
    }

    $.ajax({
        url: "/api/gudangFarmasiLimit",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-id="${item.id}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="#inputSection" class="delete"
                                    data-id="${item.id}"><i class="fas fa-trash"></i></a>`;
            });

            $("#farmasiObat")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no", className: "text-center" },
                        { data: "nmObat" },
                        { data: "pabrikan.nmPabrikan" },
                        { data: "sediaan" },
                        { data: "supplier.nmSupplier" },
                        { data: "ed" },
                        { data: "sisa" },
                    ],

                    order: [6, "asc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    pageLength: 5,
                    responsive: true,
                    lengthChange: true,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Daftar Stok Obat Farmasi",
                            filename: "Daftar Stok Obat Farmasi",
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#farmasiObat_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function simpanTransaksi() {
    var notrans = $("#notrans").val();
    var tgltrans = $("#tgltrans").val();
    var norm = $("#norm").val();
    var idFarmasi = $("#obat").val();
    var product_id = $("#productID").val();
    var qty = $("#qty").val();
    var totalKoma = $("#total").val();
    var total = totalKoma.replace(/,/g, "");
    var petugas = $("#apoteker").val();
    var dokter = $("#dokter").val();
    // Memeriksa apakah ada nilai yang kosong
    if (!norm || !notrans || !petugas || !dokter) {
        // Menampilkan notifikasi jika ada nilai yang kosong
        var dataKurang = [];
        if (!norm) dataKurang.push("Nomor Rekam Medis Belum Diisi");
        if (!notrans) dataKurang.push("Nomor Transaksi Belum Diisi");
        if (!petugas) dataKurang.push("Petugas Belum Diisi");
        if (!dokter) dataKurang.push("Dokter Belum Diisi");

        // Menampilkan notifikasi menggunakan Toast.fire
        Swal.fire({
            icon: "error",
            title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
        });
    } else {
        if (idAptk === undefined) {
            console.log("simpan");
            $.ajax({
                url: "/api/simpanFarmasi",
                type: "POST",
                data: {
                    product_id: product_id,
                    idFarmasi: idFarmasi,
                    notrans: notrans,
                    tgltrans: tgltrans,
                    qty: qty,
                    total: total,
                    petugas: petugas,
                    dokter: dokter,
                    norm: norm,
                },

                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                    });
                    populateObatOptions();
                    dataFarmasi();
                    $("#obat,#qty,#total,#productID,#tagihan").val("");
                    $("#obat").trigger("change");
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Data Tidak Lengkap...!!!",
                    });
                },
            });
        } else {
            if (prod_id !== product_id) {
                Swal.fire({
                    icon: "error",
                    title: "Tidak bisa menyimpan data...!!!",
                    text: "Silahkan Hapus transaksi terlebih dahulu untuk mngganti obat",
                });
            } else {
                console.log("update" + idAptk);

                $.ajax({
                    url: "/api/editFarmasi",
                    type: "POST",
                    data: {
                        idAptk: idAptk,
                        product_id: product_id,
                        idFarmasi: idFarmasi,
                        notrans: notrans,
                        tgltrans: tgltrans,
                        qty: qty,
                        total: total,
                    },

                    success: function (response) {
                        Toast.fire({
                            icon: "success",
                            title: "Data Berhasil diubah, Maturnuwun...!!!",
                        });
                        idAptk = undefined;
                        console.log(
                            "🚀 ~ file: mainFarmasi.js:230 ~ simpanTransaksi ~ idAptk:",
                            idAptk
                        );
                        prod_id = undefined;
                        console.log(
                            "🚀 ~ file: mainFarmasi.js:232 ~ simpanTransaksi ~ prod_id:",
                            prod_id
                        );

                        populateObatOptions();
                        dataFarmasi();
                        $("#obat,#qty,#total,#productID,#tagihan").val("");
                        $("#obat").trigger("change");
                        $("#obat").readonly = false;
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Data Tidak Lengkap...!!!",
                        });
                    },
                });
            }
        }
    }
}

async function showRiwayat() {
    if ($.fn.DataTable.isDataTable("#riwayat")) {
        var tabel = $("#riwayat").DataTable();
        tabel.clear().destroy();
    }
    try {
        const url = "/api/riwayatFarmasi";
        const norm = $("#norm").val();
        const queryParam = { norm: norm };
        const response = await fetch(url, {
            method: "POST",
            body: JSON.stringify(queryParam),
            headers: {
                "Content-Type": "application/json",
            },
        });
        const data = await response.json();
        data.forEach((item) => {
            item.tgltrans = moment(item.tgltrans).format("DD-MM-YYYY");
        });
        $("#riwayat").DataTable({
            data: data,
            columns: [
                { data: "idFarmasi" },
                { data: "norm" },
                { data: "notrans" },
                { data: "tgltrans" },
                { data: "qty" },
                { data: "total" },
                { data: "petugas" },
            ],
        });
    } catch (error) {
        console.log(error);
    }
}

function rstForm() {
    $(
        "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #apoteker, #obat, #qty,#tagihan,#productID"
    ).val("");
    $("#dokter, #apoteker, #obat").trigger("change");
    $("#add").show();
    $("#edit").hide();
    var table = $("#dataFarmasi").DataTable();
    var table2 = $("#dataIGD").DataTable();
    table.clear().destroy();
    table2.clear().destroy();
    populateObatOptions();
    antrian();
    scrollToAntrianSection();
    idAptk = undefined;
    console.log(
        "🚀 ~ file: mainFarmasi.js:230 ~ simpanTransaksi ~ idAptk:",
        idAptk
    );
    prod_id = undefined;
    console.log(
        "🚀 ~ file: mainFarmasi.js:232 ~ simpanTransaksi ~ prod_id:",
        prod_id
    );
}

function selesai() {
    $("#add").show();
    $("#edit").hide();
    var norm = $("#norm").val();
    var notrans = $("#notrans").val();
    // Memeriksa apakah ada nilai yang kosong
    if (!norm || !notrans) {
        // Menampilkan notifikasi jika ada nilai yang kosong
        var dataKurang = [];
        if (!norm || !notrans) dataKurang.push("Belum Ada Data Transaksi");

        Toast.fire({
            icon: "error",
            title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
        });
        scrollToAntrianSection();
        idAptk = undefined;
        console.log(
            "🚀 ~ file: mainFarmasi.js:230 ~ simpanTransaksi ~ idAptk:",
            idAptk
        );
        prod_id = undefined;
        console.log(
            "🚀 ~ file: mainFarmasi.js:232 ~ simpanTransaksi ~ prod_id:",
            prod_id
        );
    } else {
        $(
            "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #apoteker, #obat, #qty,#tagihan,#productID"
        ).val("");
        $("#dokter, #apoteker, #obat").trigger("change");

        var table = $("#dataFarmasi, #dataIGD").DataTable();
        var table2 = $("#dataIGD").DataTable();
        table.clear().destroy();
        table2.clear().destroy();
        populateObatOptions();
        antrian();
        scrollToAntrianSection();
        Toast.fire({
            icon: "success",
            title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
        });
    }
}

$(document).ready(function () {
    scrollToAntrianSection();
    $("#norm").focus();
    var idAptk;
    var prod_id;
    $(".select2bs4").select2({ theme: "bootstrap4" });

    $(".bmhp").select2({ theme: "bootstrap4" });

    $("#dataFarmasi, #dataIGD").DataTable();

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });

    loadFarmasi();
    setTodayDate();
    populateDokterOptions();
    populateApotekerOptions();
    populateObatOptions();
    antrianAll();

    setInterval(function () {
        antrianAll();
    }, 60000);

    $("#jual").on("input", function (e) {
        hitungTotalHarga();
    });
    $("#modal-xl").modal("show");

    $("#dataAntrian").on("click", ".panggil", function (e) {
        e.preventDefault();

        let panggilData = $(this).data("panggil");
        // panggilData = "coba panggil imam aji santoso";
        console.log(
            "🚀 ~ file: mainFarmasi.js:478 ~ panggilData:",
            panggilData
        );

        panggilPasien(panggilData);
    });

    $("#dataFarmasi").on("click", ".delete", function (e) {
        e.preventDefault();

        var idAptk = $(this).data("id");
        var nmObat = $(this).data("nmObat");

        Swal.fire({
            title: "Konfirmasi",
            text:
                "Apakah Anda yakin ingin menghapus transaksi obat " +
                nmObat +
                " ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/deleteFarmasi",
                    type: "POST",
                    data: { idAptk: idAptk },
                    success: function (response) {
                        Toast.fire({
                            icon: "success",
                            title: "Data transaksi obat berhasil dihapus...!!!",
                        });

                        dataFarmasi();
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
    });

    $("#dataFarmasi").on("click", ".edit", function (e) {
        e.preventDefault();
        // $("#add").hide();
        $("#edit").show();
        var iObat = $(this).data("idobat");
        idAptk = $(this).data("id");
        prod_id = $(this).data("product_id");
        var qty = $(this).data("qty");
        $("#obat").val(iObat).trigger("change");
        $("#qty").val(qty);
        $("#obat").readonly = true;
        hitungTotalHarga();
    });
});
