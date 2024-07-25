const Awal = document.getElementById("tglAwal");
const Akhir = document.getElementById("tglAkhir");
const jaminan = document.getElementById("jaminan");

function reportKunjungan() {
    if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
        var tabel = $("#reportKunjungan").DataTable();
        tabel.clear().destroy();
        $("#reportKunjungan thead").remove();
        $("#reportKunjungan tbody").remove();
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
            // Map response data to a structure suitable for DataTable
            var dataTableData = response.map(function (item) {
                // Clone the item to avoid modifying the original object
                var clonedItem = Object.assign({}, item);

                // Transform pemeriksaan into an object with key-value pairs
                var pemeriksaanObj = {};
                item.pemeriksaan.forEach(function (pemeriksaan) {
                    // Assign hasil to the respective pemeriksaan name
                    pemeriksaanObj[pemeriksaan.nmLayanan] = pemeriksaan.hasil;
                });

                // Add the transformed pemeriksaan object to clonedItem
                clonedItem.pemeriksaan = pemeriksaanObj;

                return clonedItem;
            });

            // Extract all unique pemeriksaan types from the response
            var uniquePemeriksaan = new Set();
            response.forEach(function (item) {
                item.pemeriksaan.forEach(function (pemeriksaan) {
                    uniquePemeriksaan.add(pemeriksaan.nmLayanan);
                });
            });

            // Create DataTable columns dynamically
            var columns = [
                {
                    data: null,
                    title: "No",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                { data: "tgl", title: "Tanggal" },
                { data: "norm", title: "Nomor Rekam Medis" },
                { data: "jaminan", title: "Jaminan" },
                {
                    data: "nama",
                    title: "Nama",
                    className: "col-2", // Set custom class for width
                    render: function (data, type, row) {
                        return data.toUpperCase();
                    },
                },
                { data: "alamat", title: "Alamat", className: "col-3" }, // Set custom class for width
                {
                    data: "dokter_nama",
                    title: "Nama Dokter",
                    className: "col-3", // Set custom class for width
                },
            ];

            // Add each unique pemeriksaan as a column with its name as title
            uniquePemeriksaan.forEach(function (pemeriksaan) {
                columns.push({
                    data: "pemeriksaan." + pemeriksaan,
                    title: pemeriksaan, // Use pemeriksaan name as column title
                    defaultContent: "-",
                });
            });

            // Initialize DataTable with dynamic columns
            $("#reportKunjungan")
                .DataTable({
                    data: dataTableData,
                    columns: columns,
                    order: [0, "asc"],
                    lengthChange: true,
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
                        "colvis", // Show/Hide Columns button
                    ],
                    // Add border style to DataTable
                    initComplete: function () {
                        this.api()
                            .table()
                            .node()
                            .classList.add("table", "table-bordered-custom");
                    },
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

// function reportKunjungan() {
//     if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
//         var tabel = $("#reportKunjungan").DataTable();
//         tabel.clear().destroy();
//         $("#reportKunjungan thead").remove();
//         $("#reportKunjungan tbody").remove();
//         $("#reportKunjungan tfoot").remove();
//     }
//     var tglAwal = document.getElementById("tglAwal").value;
//     var tglAkhir = document.getElementById("tglAkhir").value;

//     $.ajax({
//         url: "/api/rekap/Kunjungan_Lab",
//         type: "post",
//         data: {
//             tglAwal: tglAwal,
//             tglAkhir: tglAkhir,
//         },
//         success: function (response) {
//             // Map response data to a structure suitable for DataTable
//             var dataTableData = response.map(function (item) {
//                 // Clone the item to avoid modifying the original object
//                 var clonedItem = Object.assign({}, item);

//                 // Transform pemeriksaan into an object with key-value pairs
//                 var pemeriksaanObj = {};
//                 item.pemeriksaan.forEach(function (pemeriksaan) {
//                     // Assign hasil to the respective pemeriksaan name
//                     pemeriksaanObj[pemeriksaan.nmLayanan] = pemeriksaan.hasil;
//                 });

//                 // Add the transformed pemeriksaan object to clonedItem
//                 clonedItem.pemeriksaan = pemeriksaanObj;

//                 return clonedItem;
//             });

//             // Extract all unique pemeriksaan types from the response
//             var uniquePemeriksaan = new Set();
//             response.forEach(function (item) {
//                 item.pemeriksaan.forEach(function (pemeriksaan) {
//                     uniquePemeriksaan.add(pemeriksaan.nmLayanan);
//                 });
//             });

//             // Create DataTable columns dynamically
//             var columns = [
//                 {
//                     data: null,
//                     title: "No",
//                     render: function (data, type, row, meta) {
//                         return meta.row + meta.settings._iDisplayStart + 1;
//                     },
//                 },
//                 { data: "tgl", title: "Tanggal" },
//                 { data: "norm", title: "Nomor Rekam Medis" },
//                 { data: "jaminan", title: "Jaminan" },
//                 {
//                     data: "nama",
//                     title: "Nama",
//                     className: "col-2", // Set custom class for width
//                     render: function (data, type, row) {
//                         return data.toUpperCase();
//                     },
//                 },
//                 { data: "alamat", title: "Alamat", className: "col-3" }, // Set custom class for width
//                 {
//                     data: "dokter_nama",
//                     title: "Nama Dokter",
//                     className: "col-3", // Set custom class for width
//                 },
//             ];

//             // Add each unique pemeriksaan as a column with its name as title
//             uniquePemeriksaan.forEach(function (pemeriksaan) {
//                 columns.push({
//                     data: "pemeriksaan." + pemeriksaan,
//                     title: pemeriksaan, // Use pemeriksaan name as column title
//                     defaultContent: "-",
//                 });
//             });

//             // Initialize DataTable with dynamic columns
//             var table = $("#reportKunjungan")
//                 .DataTable({
//                     data: dataTableData,
//                     columns: columns,
//                     order: [0, "asc"],
//                     lengthChange: false,
//                     paging: false,
//                     autoWidth: true,
//                     buttons: [
//                         {
//                             extend: "copyHtml5",
//                             text: "Salin",
//                         },
//                         {
//                             extend: "excel",
//                             text: "Export to Excel",
//                             title:
//                                 "Laporan Hasil Pemeriksaan Lab " +
//                                 tglAwal +
//                                 " s.d. " +
//                                 tglAkhir,
//                             filename:
//                                 "Daftar Penjamin Laboratorium " +
//                                 tglAwal +
//                                 " s.d. " +
//                                 tglAkhir,
//                         },
//                         "colvis", // Show/Hide Columns button
//                     ],
//                     // Add border style to DataTable
//                     initComplete: function () {
//                         this.api()
//                             .table()
//                             .node()
//                             .classList.add("table", "table-bordered-custom");
//                     },
//                     footerCallback: function (row, data, start, end, display) {
//                         var api = this.api();
//                         var footerRow = $(api.table().footer()).find('tr');
//                         footerRow.empty();

//                         // Calculate and append totals for each column
//                         api.columns().every(function (index) {
//                             var total = 0;
//                             this.data().each(function (value) {
//                                 if (value !== "-" && !isNaN(value)) {
//                                     total += parseFloat(value);
//                                 }
//                             });
//                             var footerText = (total !== 0) ? total : "-";
//                             footerRow.append('<th>' + footerText + '</th>');
//                         });
//                     },
//                 })
//                 .buttons()
//                 .container()
//                 .appendTo("#reportKunjungan_wrapper .col-md-6:eq(0)");
//         },
//         error: function (xhr, status, error) {
//             console.error("Error:", error);
//         },
//     });
// }

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
//     // Clear existing DataTable, if initialized
//     if ($.fn.DataTable.isDataTable("#tabelJumlahPeriksa")) {
//         var table = $("#tabelJumlahPeriksa").DataTable();
//         table.clear().destroy();

//         // Remove thead and tbody
//         $("#tabelJumlahPeriksa thead").remove();
//         $("#tabelJumlahPeriksa tbody").remove();
//     }

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
//                     };
//                     newRow[item.tanggal] = item.jumlah; // Set jumlah for the specific date

//                     data.push(newRow);
//                 }
//             });

//             // Create headers for DataTable
//             var columns = [
//                 {
//                     data: null,
//                     title: "No",
//                     render: function (data, type, row, meta) {
//                         // 'meta.row' gives the index of the row DataTable is working with
//                         // 'meta.settings._iDisplayStart' gives the starting point in the current data set
//                         return meta.row + meta.settings._iDisplayStart + 1;
//                     },
//                 },
//                 { data: "kode_layanan", title: "Kode Pemeriksaan" },
//                 { data: "nama_layanan", title: "Nama Pemeriksaan" },
//             ];

//             // Add headers for each unique date
//             Object.keys(dates).forEach(function (date) {
//                 columns.push({ data: date, title: date, defaultContent: "-" }); // Ensure default content for each column
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
                // Check if data array has an entry for this kode_layanan
                var existingEntry = data.find(function (entry) {
                    return entry.kode_layanan === item.kode_layanan;
                });

                if (!existingEntry) {
                    existingEntry = {
                        kode_layanan: item.kode_layanan,
                        nama_layanan: item.nama_layanan,
                    };
                    data.push(existingEntry);
                }

                // Set jumlah for the specific date and jaminan
                var columnKey = item.tanggal + " (" + item.jaminan + ")";
                existingEntry[columnKey] = item.jumlah;

                // Track unique dates dynamically
                if (!dates[columnKey]) {
                    dates[columnKey] = true;
                }
            });

            // Create headers for DataTable
            var columns = [
                {
                    data: null,
                    title: "No",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                { data: "kode_layanan", title: "Kode Pemeriksaan" },
                { data: "nama_layanan", title: "Nama Pemeriksaan" },
            ];

            // Add headers for each unique date and jaminan
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
