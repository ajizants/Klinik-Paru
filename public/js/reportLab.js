const Awal = document.getElementById("tglAwal");
const Akhir = document.getElementById("tglAkhir");
const jaminan = document.getElementById("jaminan");

async function reportKunjungan() {
    // Swal.fire({
    //     icon: "info",
    //     title: "Sedang mencari data...!!!",
    //     allowOutsideClick: false,
    //     showConfirmButton: false,
    // });
    if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
        var tabletindakan = $("#reportKunjungan").DataTable();
        tabletindakan.clear().destroy();
    }
    const url = "/api/rekap/Kunjungan_Lab";
    var tglAwal = document.getElementById("tglAwal").value;
    var tglAkhir = document.getElementById("tglAkhir").value;

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                tglAwal: tglAwal,
                tglAkhir: tglAkhir,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const dataKunjungan = await response.json();

        // Process data kunjungan
        let processedData = [];
        dataKunjungan.forEach(function (item) {
            const patientDetails = {
                no: item.idLab,
                created_at: new Date(item.created_at).toLocaleString("id-ID", {
                    year: "numeric",
                    month: "numeric",
                    day: "numeric",
                }),
                norm: item.norm,
                nik: item.nik,
                layanan: item.layanan,
                nama: item.nama,
                alamat: item.alamat,
                dokter:
                    item.dokter.gelar_d +
                    " " +
                    item.dokter.biodata.nama +
                    " " +
                    item.dokter.gelar_b,
                petugas:
                    item.petugas.gelar_d +
                    " " +
                    item.petugas.biodata.nama +
                    " " +
                    item.petugas.gelar_b,
            };

            item.pemeriksaan.forEach(function (pemeriksaan, pemeriksaanIndex) {
                const examDetails = {
                    pemeriksaan: `<b>Pemeriksaan:</b> ${pemeriksaan.pemeriksaan.nmLayanan}`,
                    petugasLab: `<b>Petugas:</b> ${pemeriksaan.petugas.biodata.nama}`,
                };

                // Add patient details only for the first examination row
                if (pemeriksaanIndex === 0) {
                    processedData.push({
                        ...patientDetails,
                        ...examDetails,
                        totalPemeriksaan: item.pemeriksaan.length,
                    });
                } else {
                    processedData.push({
                        ...examDetails,
                        isFirst: false, // Flag to distinguish the first examination row
                    });
                }
            });
        });

        // Initialize DataTables with the processed data
        $("#reportKunjungan").DataTable({
            data: processedData,
            columns: [
                { data: "idLab", width: "15px", visible: false }, // Hide the number column
                { data: "created_at" },
                { data: "norm" },
                { data: "nik" },
                { data: "layanan" },
                { data: "nama" },
                { data: "alamat" },
                { data: "dokter" },
                { data: "pemeriksaan" },
                { data: "petugasLab" },
            ],
            rowCallback: function (row, data, index) {
                // Set rowspan for patient details columns
                if (data.isFirst || data.totalPemeriksaan === 1) {
                    $("td:eq(0)", row).attr("rowspan", data.totalPemeriksaan);
                    $("td:eq(1)", row).attr("rowspan", data.totalPemeriksaan);
                    $("td:eq(2)", row).attr("rowspan", data.totalPemeriksaan);
                    $("td:eq(3)", row).attr("rowspan", data.totalPemeriksaan);
                    $("td:eq(4)", row).attr("rowspan", data.totalPemeriksaan);
                    $("td:eq(5)", row).attr("rowspan", data.totalPemeriksaan);
                    $("td:eq(6)", row).attr("rowspan", data.totalPemeriksaan);
                    $("td:eq(7)", row).attr("rowspan", data.totalPemeriksaan);
                }
            },
            drawCallback: function () {
                // Remove duplicated patient details cells in subsequent rows
                $("#reportKunjungan tbody")
                    .find("tr")
                    .each(function () {
                        const firstRowData = $(this).data("rowData");
                        $(this)
                            .find("td")
                            .each(function () {
                                const colIndex = $(this).index();
                                if (
                                    colIndex >= 0 &&
                                    colIndex <= 7 &&
                                    !firstRowData.isFirst
                                ) {
                                    $(this).remove();
                                }
                            });
                    });
            },
        });
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

function reportKunjungan2() {
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
        url: "/api/rekapKunjungan",
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
                    data: dataKunjungan,
                    columns: [
                        { data: "no" },
                        {
                            data: "TglTrans",
                            render: function (data) {
                                // Format the date using JavaScript
                                const formattedDate = new Date(
                                    data
                                ).toLocaleString("id-ID", {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                });
                                return formattedDate;
                            },
                        },
                        { data: "Norm" },
                        { data: "NamaPasien" },
                        { data: "NIKPasien" },
                        { data: "AlamatLengkap" },
                        { data: "JenisKelamin" },
                        { data: "Jaminan" },
                        {
                            data: "RiwayatLab",
                            render: function (data) {
                                // Customize the content for display
                                return (
                                    '<div class="riwayatContainer" >' +
                                    '<table class="childTable">' +
                                    "<tbody>" +
                                    data
                                        .map(function (riwayat) {
                                            return (
                                                "<tr>" +
                                                "<td>" +
                                                riwayat.NoTrans +
                                                "</td>" +
                                                "<td>" +
                                                riwayat.Norm +
                                                "</td>" +
                                                "<td>" +
                                                riwayat.NmLayanan +
                                                "</td>" +
                                                "<td>" +
                                                riwayat.Tarif +
                                                "</td>" +
                                                "</tr>"
                                            );
                                        })
                                        .join("") +
                                    "</tbody>" +
                                    "</table>" +
                                    "</div>"
                                );
                            },
                        },
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
                            extend: "excel",
                            text: "Export to Excel",
                            title:
                                "Daftar Penjamin Laboratorium  " +
                                tglAwal +
                                " s.d. " +
                                tglAkhir,
                            filename:
                                "Daftar Penjamin Laboratorium  " +
                                tglAwal +
                                " s.d. " +
                                tglAkhir,
                            customize: function (xlsx) {
                                var sheet = xlsx.xl.worksheets["sheet1.xml"];

                                // Iterate over the rows to find child rows and include them in the Excel sheet
                                $('row c[r^="B"]', sheet).each(function () {
                                    var childRow = $(this)
                                        .closest("row")
                                        .next("row");
                                    if (childRow.length) {
                                        childRow.find("c").each(function () {
                                            $(this).attr("s", "37");
                                        });
                                    }
                                });
                            },
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
function reportPenjamin() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencari data...!!!",
        allowOutsideClick: false,
        showConfirmButton: false,
    });
    if ($.fn.DataTable.isDataTable("#reportPenjamin")) {
        var tabletindakan = $("#reportPenjamin").DataTable();
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
            console.log("🚀 ~ reportPenjamin ~ response:", response);
            $("#reportPenjamin")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        {
                            data: "created_at",
                            render: function (data) {
                                // Format the date using JavaScript
                                const formattedDate = new Date(
                                    data
                                ).toLocaleString("id-ID", {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                });
                                return formattedDate;
                            },
                        },
                        { data: "kelompok" },
                        { data: "nmLayanan" },
                        { data: "Jumlah" },
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
                                "Daftar Penjamin Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                            filename:
                                "Daftar Penjamin Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#reportPenjamin_wrapper .col-md-6:eq(0)");
            Swal.close();
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function reportReagen() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencari data...!!!",
        allowOutsideClick: false,
        showConfirmButton: false,
    });
    if ($.fn.DataTable.isDataTable("#reportReagen")) {
        var tabletindakan = $("#reportReagen").DataTable();
        tabletindakan.clear().destroy();
    }
    let tglAwalValue = tglAwal.value;
    let tglAkhirValue = tglAkhir.value;
    let formattedAwal = tglAwalValue.split("-").reverse().join("-");
    let formattedAkhir = tglAkhirValue.split("-").reverse().join("-");
    // let jaminanValue = jaminan.value;
    $.ajax({
        url: "/api/rekapReagenHari",
        type: "post",
        data: {
            tglAwal: tglAwalValue,
            tglAkhir: tglAkhirValue,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });
            console.log("🚀 ~ reportReagen ~ response:", response);
            $("#reportReagen")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        {
                            data: "created_at",
                            render: function (data) {
                                // Format the date using JavaScript
                                const formattedDate = new Date(
                                    data
                                ).toLocaleString("id-ID", {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                });
                                return formattedDate;
                            },
                        },
                        { data: "nmLayanan" },
                        { data: "Jumlah" },
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
                                "Rekap Penggunaan Reagen Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                            filename:
                                "Rekap Penggunaan Reagen Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#reportPenjamin_wrapper .col-md-6:eq(0)");
            Swal.close();
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function reportReagenBln() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencari data...!!!",
        allowOutsideClick: false,
        showConfirmButton: false,
    });
    if ($.fn.DataTable.isDataTable("#reportReagen")) {
        var tabletindakan = $("#reportReagen").DataTable();
        tabletindakan.clear().destroy();
    }
    let tglAwalValue = tglAwal.value;
    let tglAkhirValue = tglAkhir.value;
    let formattedAwal = tglAwalValue.split("-").reverse().join("-");
    let formattedAkhir = tglAkhirValue.split("-").reverse().join("-");
    // let jaminanValue = jaminan.value;
    $.ajax({
        url: "/api/rekapReagenBln",
        type: "post",
        data: {
            tglAwal: tglAwalValue,
            tglAkhir: tglAkhirValue,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                const monthYearArray = item.created_at.split("-");
                const month = parseInt(monthYearArray[0]);
                const year = parseInt(monthYearArray[1]);
                const formattedDate = new Date(
                    year,
                    month - 1,
                    1
                ).toLocaleDateString("id-ID", {
                    month: "long",
                    year: "numeric",
                });

                item.created_at = formattedDate;
            });
            console.log("🚀 ~ reportReagen ~ response:", response);
            $("#reportReagen")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        {
                            data: "created_at",
                            render: function (data) {
                                // Parse the date using a specific format
                                const parsedDate = moment(data, "MMMM YYYY");

                                // Format the date in Indonesian locale
                                const formattedDate = parsedDate
                                    .locale("id")
                                    .format("MMMM YYYY");

                                return formattedDate;
                            },
                        },
                        { data: "nmLayanan" },
                        { data: "Jumlah" },
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
                                "Rekap Penggunaan Reagen Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                            filename:
                                "Rekap Penggunaan Reagen Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#reportReagen_wrapper .col-md-6:eq(0)");
            Swal.close();
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function reportPoin() {
    if ($.fn.DataTable.isDataTable("#reportPoin")) {
        var tabletindakan = $("#reportPoin").DataTable();
        tabletindakan.clear().destroy();
    }
    let tglAwalValue = tglAwal.value;
    let tglAkhirValue = tglAkhir.value;
    let formattedAwal = tglAwalValue.split("-").reverse().join("-");
    let formattedAkhir = tglAkhirValue.split("-").reverse().join("-");
    // let jaminanValue = jaminan.value;
    $.ajax({
        url: "/api/poinLab",
        type: "post",
        data: {
            tglAwal: tglAwalValue,
            tglAkhir: tglAkhirValue,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                // const monthYearArray = item.created_at.split("-");
                // const month = parseInt(monthYearArray[0]);
                // const year = parseInt(monthYearArray[1]);
                // const formattedDate = new Date(
                //     year,
                //     month - 1,
                //     1
                // ).toLocaleDateString("id-ID", {
                //     month: "long",
                //     year: "numeric",
                // });
                // item.created_at = formattedDate;
            });
            console.log("🚀 ~ reportPoin ~ response:", response);
            $("#reportPoin")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "nip" },
                        { data: "nama" },
                        { data: "tindakan" },
                        { data: "jml" },
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
                                "Rekap Penggunaan Reagen Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                            filename:
                                "Rekap Penggunaan Reagen Laboratorium  " +
                                formattedAwal +
                                " s.d. " +
                                formattedAkhir,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#reportPoin_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
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
    $("#penjamin").hide();
    $("#cariPenjamin").hide();
    $("#hasil").hide();
    $("#cariHasil").hide();
    $("#reagen").hide();
    $("#cariReagen").hide();
    $("#cariReagenBln").hide();
    $("#poin").hide();
    $("#cariPoin").hide();
}
function showPenjamin() {
    $("#penjamin").show();
    $("#cariPenjamin").show();
    $("#kunjungan").hide();
    $("#cariKunjungan").hide();
    $("#hasil").hide();
    $("#cariHasil").hide();
    $("#reagen").hide();
    $("#cariReagen").hide();
    $("#cariReagenBln").hide();
    $("#poin").hide();
    $("#cariPoin").hide();
}

function showReagen() {
    $("#kunjungan").hide();
    $("#cariKunjungan").hide();
    $("#penjamin").hide();
    $("#cariPenjamin").hide();
    $("#hasil").hide();
    $("#cariHasil").hide();
    $("#reagen").show();
    $("#cariReagen").show();
    $("#cariReagenBln").show();
    $("#poin").hide();
    $("#cariPoin").hide();
}
function showHasil() {
    $("#kunjungan").hide();
    $("#cariKunjungan").hide();
    $("#penjamin").hide();
    $("#cariPenjamin").hide();
    $("#reagen").hide();
    $("#cariReagen").hide();
    $("#cariReagenBln").hide();
    $("#hasil").show();
    $("#cariHasil").show();
    $("#poin").hide();
    $("#cariPoin").hide();
}
function showPoin() {
    $("#kunjungan").hide();
    $("#cariKunjungan").hide();
    $("#penjamin").hide();
    $("#cariPenjamin").hide();
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
