let tglAwal = document.getElementById("tglAwal");
let tglAkhir = document.getElementById("tglAkhir");
let jaminan = document.getElementById("jaminan");

function reportKunjungan() {
    if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
        var tabletindakan = $("#reportKunjungan").DataTable();
        tabletindakan.clear().destroy();
    }
    let tglAwalValue = tglAwal.value;
    let tglAkhirValue = tglAkhir.value;
    let formattedAwal = tglAwalValue.split("-").reverse().join("-");
    let formattedAkhir = tglAkhirValue.split("-").reverse().join("-");
    // let jaminanValue = jaminan.value;
    $.ajax({
        url: "/api/rekapBpjsUmum",
        type: "post",
        data: {
            tglAwal: tglAwalValue,
            tglAkhir: tglAkhirValue,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });
            console.log("🚀 ~ reportKunjungan ~ response:", response);
            $("#reportKunjungan")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        { data: "created_at" },
                        { data: "nmLayanan" },
                        { data: "Jumlah" },
                        { data: "kelompok" },
                    ],
                    order: [0, "asc"],
                    lengthChange: false,
                    autoWidth: true,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Daftar Kunjungan Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                            filename:
                                "Daftar Kunjungan Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#reportKunjungan_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function reportReagen() {
    console.log("🚀 ~ reportReagen ~ reportReagen:", reportReagen);
    // Use the .value property to get the values
    let tglAwalValue = tglAwal.value;
    let tglAkhirValue = tglAkhir.value;
}
function reportHasil() {
    console.log("🚀 ~ reportHasil ~ reportHasil:", reportHasil);
    // Use the .value property to get the values
    let tglAwalValue = tglAwal.value;
    let tglAkhirValue = tglAkhir.value;
}

function showKunjungan() {
    $("#kunjungan").show();
    $("#cariKunjungan").show();
    $("#hasil").hide();
    $("#cariHasil").hide();
    $("#reagen").hide();
    $("#cariReagen").hide();
    $("#poin").hide();
    $("#cariPoin").hide();
}

function showReagen() {
    $("#kunjungan").hide();
    $("#cariKunjungan").hide();
    $("#hasil").hide();
    $("#cariHasil").hide();
    $("#reagen").show();
    $("#cariReagen").show();
    $("#poin").hide();
    $("#cariPoin").hide();
}
function showHasil() {
    $("#kunjungan").hide();
    $("#cariKunjungan").hide();
    $("#reagen").hide();
    $("#cariReagen").hide();
    $("#hasil").show();
    $("#cariHasil").show();
    $("#poin").hide();
    $("#cariPoin").hide();
}
function showPoin() {
    $("#kunjungan").hide();
    $("#cariKunjungan").hide();
    $("#reagen").hide();
    $("#cariReagen").hide();
    $("#hasil").hide();
    $("#cariHasil").hide();
    $("#poin").show();
    $("#cariPoin").show();
}

document.addEventListener("DOMContentLoaded", function () {
    // Check if elements are found before setting their values
    if (tglAwal && tglAkhir) {
        let today = new Date();
        tglAkhir.value = today.toISOString().split("T")[0];
        tglAwal.value = today.toISOString().split("T")[0];
    } else {
        console.error("Error: One or both elements not found.");
    }
    populateJaminan();
    showKunjungan();
});
