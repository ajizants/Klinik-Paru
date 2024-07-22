const Awal = document.getElementById("tglAwal");
const Akhir = document.getElementById("tglAkhir");
const jaminan = document.getElementById("jaminan");

async function reportKunjungan2() {
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

function reportKunjungan() {
    if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
        var tabel = $("#reportKunjungan").DataTable();
        tabel.clear().destroy();
    }
    var tglAwal = document.getElementById("tglAwal").value;
    var tglAkhir = document.getElementById("tglAkhir").value;

    $.ajax({
        url: "/api/rekap/Kunjungan_Lab",
        type: "post",
        data: {
            tglAwal: tglAwal,
            tglAkhir: tglAkhir,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });
            // console.log("🚀 ~ reportKunjungan ~ response:", response);
            $("#reportKunjungan")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        { data: "tgl" },
                        { data: "norm" },
                        { data: "jaminan" },
                        {
                            data: "nama",
                            render: function (data, type, row) {
                                return data.toUpperCase();
                            },
                        },
                        { data: "alamat" },
                        { data: "dokter_nama" },
                        { data: "petugas_nama" },
                        { data: "pemeriksaan" },
                        { data: "hasil" },
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
                                "Laporan Hasil Pemeriksaan Lab  " +
                                tglAwal +
                                " s.d. " +
                                tglAkhir,
                            filename:
                                "Daftar Penjamin Laboratorium  " +
                                tglAwal +
                                " s.d. " +
                                tglAkhir,
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

function reportHasil() {
    console.log("🚀 ~ reportHasil ~ reportHasil:", reportHasil);
    // Use the .value property to get the values
    let tglAwalValue = tglAwal.value;
    let tglAkhirValue = tglAkhir.value;
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
        url: "/api/rekap/lab/poin",
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

// function reportJumlahPemeriksaan() {
//     var tglAwal = document.getElementById("tglAwal").value;
//     var tglAkhir = document.getElementById("tglAkhir").value;

//     $.ajax({
//         url: "/api/rekap/lab/jumlah_pemeriksaan",
//         type: "post",
//         data: {
//             tglAwal: tglAwal,
//             tglAkhir: tglAkhir,
//         },
//         success: function (response) {
//             if ($.fn.DataTable.isDataTable("#tabelJumlahPeriksa")) {
//                 $("#tabelJumlahPeriksa").DataTable().destroy();
//             }

//             // Prepare data structure for DataTable
//             var data = [];
//             var dates = {}; // To store unique dates dynamically

//             // Process each item in the response
//             response.forEach(function (item) {
//                 if (!dates[item.tanggal]) {
//                     dates[item.tanggal] = true; // Use an object to track unique dates
//                 }

//                 // Check if data array has an entry for this kode_layanan
//                 var existingEntry = data.find(function (entry) {
//                     return entry.kode_layanan === item.kode_layanan;
//                 });

//                 if (existingEntry) {
//                     // Update existing entry for this kode_layanan
//                     existingEntry[item.tanggal] = item.jumlah;
//                 } else {
//                     // Create new entry for this kode_layanan
//                     var newRow = {
//                         kode_layanan: item.kode_layanan,
//                         nama_layanan: item.nama_layanan,
//                         [item.tanggal]: item.jumlah,
//                     };
//                     data.push(newRow);
//                 }
//             });

//             // Create headers for DataTable
//             var columns = [
//                 { data: "kode_layanan", title: "Kode Pemeriksaan" },
//                 { data: "nama_layanan", title: "Nama Pemeriksaan" },
//             ];

//             // Add headers for each unique date
//             Object.keys(dates).forEach(function (date) {
//                 columns.push({ data: date, title: date });
//             });

//             // Initialize DataTable
//             var table = $("#tabelJumlahPeriksa").DataTable({
//                 data: data,
//                 columns: columns,
//                 order: [[0, "asc"]],
//                 lengthChange: false,
//                 autoWidth: true,
//                 buttons: [
//                     {
//                         extend: "copyHtml5",
//                         text: "Salin",
//                     },
//                     {
//                         extend: "excel",
//                         text: "Export to Excel",
//                         title:
//                             "Laporan Hasil Pemeriksaan Lab " +
//                             tglAwal +
//                             " s.d. " +
//                             tglAkhir,
//                         filename:
//                             "Daftar Penjamin Laboratorium " +
//                             tglAwal +
//                             " s.d. " +
//                             tglAkhir,
//                     },
//                     "colvis", // Button to show/hide columns
//                 ],
//             });

//             // Append buttons to the DataTable
//             table
//                 .buttons()
//                 .container()
//                 .appendTo("#tabelJumlahPeriksa_wrapper .col-md-6:eq(0)");
//         },
//         error: function (xhr, status, error) {
//             console.error("Error:", error);
//         },
//     });
// }

function reportJumlahPemeriksaan() {
    var tglAwal = document.getElementById("tglAwal").value;
    var tglAkhir = document.getElementById("tglAkhir").value;
    // Clear existing DataTable, if initialized
    if ($.fn.DataTable.isDataTable("#tabelJumlahPeriksa")) {
        var table = $("#tabelJumlahPeriksa").DataTable();
        table.clear().destroy();

        // Remove thead and tbody
        $("#tabelJumlahPeriksa thead").remove();
        $("#tabelJumlahPeriksa tbody").remove();
    }

    $.ajax({
        url: "/api/rekap/lab/jumlah_pemeriksaan",
        type: "post",
        data: {
            tglAwal: tglAwal,
            tglAkhir: tglAkhir,
        },
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#tabelJumlahPeriksa")) {
                $("#tabelJumlahPeriksa").DataTable().destroy();
            }

            // Prepare data structure for DataTable
            var data = [];
            var dates = {}; // To store unique dates dynamically

            // Process each item in the response
            response.forEach(function (item) {
                if (!dates[item.tanggal]) {
                    dates[item.tanggal] = true; // Use an object to track unique dates
                }

                // Check if data array has an entry for this kode_layanan
                var existingEntry = data.find(function (entry) {
                    return entry.kode_layanan === item.kode_layanan;
                });

                if (existingEntry) {
                    // Update existing entry for this kode_layanan
                    existingEntry[item.tanggal] = item.jumlah;
                } else {
                    // Create new entry for this kode_layanan
                    var newRow = {
                        kode_layanan: item.kode_layanan,
                        nama_layanan: item.nama_layanan,
                    };
                    newRow[item.tanggal] = item.jumlah; // Set jumlah for the specific date

                    data.push(newRow);
                }
            });

            // Create headers for DataTable
            var columns = [
                {
                    data: null,
                    title: "No",
                    render: function (data, type, row, meta) {
                        // 'meta.row' gives the index of the row DataTable is working with
                        // 'meta.settings._iDisplayStart' gives the starting point in the current data set
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                { data: "kode_layanan", title: "Kode Pemeriksaan" },
                { data: "nama_layanan", title: "Nama Pemeriksaan" },
            ];

            // Add headers for each unique date
            Object.keys(dates).forEach(function (date) {
                columns.push({ data: date, title: date, defaultContent: "-" }); // Ensure default content for each column
            });

            // Initialize DataTable
            var table = $("#tabelJumlahPeriksa").DataTable({
                data: data,
                columns: columns,
                order: [[0, "asc"]],
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
                            "Laporan Hasil Pemeriksaan Lab " +
                            tglAwal +
                            " s.d. " +
                            tglAkhir,
                        filename:
                            "Daftar Penjamin Laboratorium " +
                            tglAwal +
                            " s.d. " +
                            tglAkhir,
                    },
                    "colvis", // Button to show/hide columns
                ],
            });

            // Append buttons to the DataTable
            table
                .buttons()
                .container()
                .appendTo("#tabelJumlahPeriksa_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
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
});
