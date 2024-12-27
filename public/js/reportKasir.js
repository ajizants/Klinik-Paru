function reportPendapatanItem(tglAwal, tglAkhir) {
    if ($.fn.DataTable.isDataTable("#tabelPerItemUMUM,#tabelPerItemBPJS")) {
        var tabel = $("#tabelPerItemUMUM,#tabelPerItemBPJS").DataTable();
        tabel.clear().destroy();
    }
    $.ajax({
        url: "/api/pendapatan/item",
        type: "post",
        data: {
            tglAwal: tglAwal,
            tglAkhir: tglAkhir,
        },
        success: function (response) {
            Swal.close();
            const dataUmum = response.umum;
            const dataBpjs = response.bpjs;
            console.log("🚀 ~ reportPendapatanItem ~ dataUmum:", dataUmum);
            isiTabelPendapatanItem(dataUmum, "#tabelPerItemUMUM");
            isiTabelPendapatanItem(dataBpjs, "#tabelPerItemBPJS");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function isiTabelPendapatanItem(data, id) {
    data.forEach(function (item, index) {
        item.no = index + 1;
    });
    $(id)
        .DataTable({
            data: data,
            columns: [
                { data: "no" },
                { data: "nmLayanan" },
                {
                    data: "tanggal",
                    className: "col-2",
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
                {
                    data: "jumlah",
                    render: function (data, type, row) {
                        var formattedTarif = parseInt(data).toLocaleString(
                            "id-ID",
                            {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            }
                        );
                        return `${formattedTarif}`;
                    },
                },
                { data: "totalItem" },
            ],
            order: [0, "asc"],
            lengthChange: true,
            autoWidth: true,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Download",
                    title:
                        "Laporan Pendapatan Per Item Tanggal: " +
                        tglAwal +
                        " s.d. " +
                        tglAkhir,
                    filename:
                        "Laporan Pendapatan Per Item Tanggal: " +
                        tglAwal +
                        "  s.d. " +
                        tglAkhir,
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(id + "_wrapper .col-md-6:eq(0)");
}

function reportPendapatanRuang(tglAwal, tglAkhir) {
    if ($.fn.DataTable.isDataTable("#tabelPerRuangUMUM,#tabelPerRuangBPJS")) {
        var tabel = $("#tabelPerRuangUMUM,#tabelPerRuangBPJS").DataTable();
        tabel.clear().destroy();
    }
    // var tglAwal = document.getElementById("tglAwal").value;
    // var tglAkhir = document.getElementById("tglAkhir").value;

    $.ajax({
        url: "/api/pendapatan/ruang",
        type: "post",
        data: {
            tglAwal: tglAwal,
            tglAkhir: tglAkhir,
        },
        success: function (response) {
            Swal.close();
            const dataUmum = response.umum;
            const dataBpjs = response.bpjs;
            console.log("🚀 ~ reportPendapatanRuang ~ dataUmum:", dataUmum);
            isiTabelPendapatanRuang(dataUmum, "#tabelPerRuangUMUM");
            isiTabelPendapatanRuang(dataBpjs, "#tabelPerRuangBPJS");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function isiTabelPendapatanRuang(data, id) {
    data.forEach(function (item, index) {
        item.no = index + 1;
    });
    $(id)
        .DataTable({
            data: data,
            columns: [
                { data: "no" },
                { data: "nmKelas" },
                {
                    data: "tanggal",
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
                {
                    data: "jumlah",
                    render: function (data, type, row) {
                        var formattedTarif = parseInt(data).toLocaleString(
                            "id-ID",
                            {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            }
                        );
                        return `${formattedTarif}`;
                    },
                },
                { data: "totalItem" },
            ],
            order: [0, "asc"],
            lengthChange: true,
            autoWidth: true,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Download",
                    title:
                        "Laporan Pendapatan Per Ruang Tanggal: " +
                        tglAwal +
                        " s.d. " +
                        tglAkhir,
                    filename:
                        "Laporan Pendapatan Per Ruang Tanggal: " +
                        tglAwal +
                        "  s.d. " +
                        tglAkhir,
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(id + "_wrapper .col-md-6:eq(0)");
}

function reportPendapatanTotalPerHari(tahun) {
    // Destroy existing DataTables if they exist
    const tableIds = [
        "#tabelPendapatanTotalPerHariUMUM",
        "#tabelPendapatanTotalPerHariBPJS",
    ];

    tableIds.forEach((tableId) => {
        if ($.fn.DataTable.isDataTable(tableId)) {
            $(tableId).DataTable().clear().destroy();
        }
    });

    console.log("🚀 ~ reportPendapatanTotalPerHari ~ tahun:", tahun);

    // Fetch data via AJAX
    $.ajax({
        url: `/api/pendapatan/${tahun}`,
        type: "GET",
        success: function (response) {
            Swal.close();

            // Initialize DataTables for each dataset
            isiTabelPendapatanTotalPerHari(
                response.umum,
                "#tabelPendapatanTotalPerHariUMUM",
                tahun,
                "umum"
            );
            isiTabelPendapatanTotalPerHari(
                response.bpjs,
                "#tabelPendapatanTotalPerHariBPJS",
                tahun,
                "bpjs"
            );
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Terjadi kesalahan!",
                text: `Gagal mengambil data: ${error}`,
            });
        },
    });
}

function isiTabelPendapatanTotalPerHari(data, tableId, tahun, selector) {
    console.log("🚀 ~ isiTabelPendapatanTotalPerHari ~ tableId:", tableId);
    console.log("🚀 ~ isiTabelPendapatanTotalPerHari ~ data:", data);
    console.log("🚀 ~ isiTabelPendapatanTotalPerHari ~ selector:", selector);

    // Enrich data for rendering
    data.forEach((item, index) => {
        item.no = index + 1;
        item.aksi = `
            <a class="btn btn-sm btn-warning mr-2 mb-2"
                href="/api/cetakBAPH/${item.tanggal}/${tahun}/${selector}"
                target="_blank">
                Cetak BAPH ${selector}
            </a>
            <a class="btn btn-sm btn-success mr-2 mb-2"
                href="/api/cetakSBS/${item.tanggal}/${tahun}/${selector}"
                target="_blank">
                Cetak SBS ${selector}
            </a>
        `;
    });

    // Initialize DataTable
    $(tableId)
        .DataTable({
            data: data,
            columns: [
                { data: "aksi", className: "text-center col-4" },
                { data: "no", className: "text-center" },
                { data: "tanggal", className: "text-center" },
                { data: "nomor", className: "text-center" },
                { data: "kode_akun" },
                { data: "uraian" },
                { data: "jumlah", className: "text-right" },
                { data: "pendapatan", className: "text-right" },
            ],
            autoWidth: false,
            order: [[1, "asc"]],
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: `Laporan Pendapatan Tahun: ${tahun}`,
                    filename: `Laporan Pendapatan Tahun ${tahun}`,
                },
                {
                    extend: "colvis",
                    text: "Tampilkan Kolom",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
}

function cetakSBS() {
    const tgl = new Date();
    const tahun = tgl.getFullYear();
    const bulan = String(tgl.getMonth() + 1).padStart(2, "0");
    const tanggal = String(tgl.getDate()).padStart(2, "0");
    const tglSBS = `${tanggal}-${bulan}-${tahun}`;
    console.log("🚀 ~ cetakSBS ~ tglSBS:", tglSBS);

    window.open("api/cetakSBS/" + tglSBS);
}

function reportKunjungan(tglAwal, tglAkhir) {
    if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
        var tabel = $("#reportKunjungan").DataTable();
        tabel.clear().destroy();
        $("#reportKunjungan thead").remove();
        $("#reportKunjungan tbody").remove();
    }
    // var tglAwal = document.getElementById("tglAwal").value;
    // var tglAkhir = document.getElementById("tglAkhir").value;

    $.ajax({
        url: "/api/kasir/rekap",
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
                    pemeriksaanObj[pemeriksaan.nmLayanan] =
                        pemeriksaan.totalHarga;
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
                { data: "norm", title: "NoRM" },
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
                    data: "tagihan",
                    title: "Tagihan",

                    render: function (data, type, row) {
                        var formattedTarif = parseInt(data).toLocaleString(
                            "id-ID",
                            {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            }
                        );
                        return `${formattedTarif}`;
                    },
                },
                {
                    data: "bayar",
                    title: "Bayar",

                    render: function (data, type, row) {
                        var formattedTarif = parseInt(data).toLocaleString(
                            "id-ID",
                            {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            }
                        );
                        return `${formattedTarif}`;
                    },
                },
                {
                    data: "kembalian",
                    title: "Kembalian",

                    render: function (data, type, row) {
                        var formattedTarif = parseInt(data).toLocaleString(
                            "id-ID",
                            {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            }
                        );
                        return `${formattedTarif}`;
                    },
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
                    order: [0, "dsc"],
                    lengthChange: true,
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50],
                        [5, 10, 25, 50],
                    ],
                    pageLength: 5,
                    autoWidth: true,
                    buttons: [
                        {
                            extend: "excel",
                            text: "Download",
                            title:
                                "Laporan Kunjungan Kasir " +
                                tglAwal +
                                " s.d. " +
                                tglAkhir,
                            filename:
                                "Daftar Kunjungan Kasir " +
                                tglAwal +
                                " s.d. " +
                                tglAkhir,
                        },
                    ],
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

function reportPendaftaran(tglAwal, tglAkhir) {
    var tglA = formatDate(new Date(tglAwal));
    var tglB = formatDate(new Date(tglAkhir));

    if ($.fn.DataTable.isDataTable("#report, #total")) {
        var tabletindakan = $("#report, #total").DataTable();
        tabletindakan.destroy();
    }

    $.ajax({
        url: "/api/kominfo/pendaftaran/report",
        type: "post",
        data: {
            tanggal_awal: tglAwal,
            tanggal_akhir: tglAkhir,
            no_rm: "",
        },
        success: function (response) {
            var pendaftaran = response["data"];
            var total = response["total"];
            // console.log("🚀 ~ reportPendaftaran ~ total:", total);
            // console.log("🚀 ~ reportPendaftaran ~ $data:", pendaftaran);

            pendaftaran.forEach(function (item, index) {
                var nama_pasien = item.pasien_nama.replace(/'/g, "\\'");
                let resume;
                if (item.penjamin_nama == "BPJS") {
                    resume = "";
                } else {
                    resume = "hidden";
                }
                item.aksi = `

                            <button type="button" class="btn btn-sm btn-primary mr-2 mb-2"
                                    onclick="cetak('${item.pasien_no_rm}')" placeholder="Cetak">Label</button>
                            <button type="button" class="btn btn-sm btn-${item.check_in} mr-2 mb-2" id="checkin" placeholder="Selesai" data-toggle="modal"
                                    data-target="#modalSep" onclick="isiForm('${item.pasien_no_rm}', '${nama_pasien}','${item.penjamin_nama}','${item.no_trans}','${item.no_sep}',this)">
                                    <i class="fa-regular fa-square-check"></i></button>
                            <a type="button" class="btn btn-sm btn-warning mr-2 mb-2" placeholder="Resume"
                                    href="/api/resume/${item.pasien_no_rm}/${item.tanggal}" target="_blank">Resume</a>
                            `;
                if (item.check_in == "danger") {
                    item.status = "Belum";
                } else {
                    item.status = "Selesai";
                }
            });

            // $("#report")
            //     .DataTable({
            //         data: pendaftaran,
            //         columns: [
            //             { data: "aksi", className: "col-3" },
            //             { data: "antrean_nomor" },
            //             { data: "tanggal" },
            //             { data: "no_sep" },
            //             { data: "penjamin_nama" },
            //             { data: "daftar_by" },
            //             { data: "pasien_lama_baru" },
            //             { data: "pasien_no_rm" },
            //             { data: "pasien_nama", className: "col-2" },
            //             { data: "jenis_kelamin_nama" },
            //             { data: "pasien_umur" },
            //             { data: "pasien_alamat", className: "col-2" },
            //             { data: "poli_nama" },
            //             { data: "dokter_nama", className: "col-2" },
            //             {
            //                 data: "status",
            //                 render: function (data) {
            //                     const statusClasses = {
            //                         Belum: "danger",
            //                         Selesai: "success",
            //                         default: "secondary",
            //                     };
            //                     return `<div class="badge badge-${
            //                         statusClasses[data] || statusClasses.default
            //                     }">${data}</div>`;
            //                 },
            //             },
            //         ],
            //         autoWidth: false,
            //         order: [
            //             [14, "asc"],
            //             [1, "asc"],
            //         ],
            //         buttons: [
            //             {
            //                 extend: "excelHtml5",
            //                 text: "Excel",
            //                 title:
            //                     "Laporan Pendaftaran Tanggal: " +
            //                     tglA +
            //                     " s.d. " +
            //                     tglB,
            //                 filename:
            //                     "Laporan Pendaftaran Tanggal: " +
            //                     tglA +
            //                     "  s.d. " +
            //                     tglB,
            //             },
            //             {
            //                 extend: "colvis",
            //                 text: "Tampilkan Kolom",
            //             },
            //             // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
            //         ],
            //     })
            //     .buttons()
            //     .container()
            //     .appendTo("#report_wrapper .col-md-6:eq(0)");
            $("#total")
                .DataTable({
                    data: [total],
                    columns: [
                        { data: "jumlah_no_antrian", className: "text-center" },
                        { data: "jumlah_pasien", className: "text-center" },
                        {
                            data: "jumlah_pasien_batal",
                            className: "text-center",
                        },
                        { data: "jumlah_nomor_skip", className: "text-center" },
                        { data: "jumlah_BPJS", className: "text-center" },
                        { data: "jumlah_UMUM", className: "text-center" },
                        {
                            data: "jumlah_pasien_LAMA",
                            className: "text-center",
                        },
                        {
                            data: "jumlah_pasien_BARU",
                            className: "text-center",
                        },
                        { data: "jumlah_daftar_OTS", className: "text-center" },
                        { data: "jumlah_daftar_JKN", className: "text-center" },
                    ],
                    autoWidth: false,
                    ordering: false,
                    paging: true,
                    searching: false,
                    lengthChange: false,
                    buttons: [
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Laporan Pendaftaran Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Laporan Pendaftaran Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        {
                            extend: "colvis",
                            text: "Tampilkan Kolom",
                        },
                        // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#total_wrapper .col-md-6:eq(0)");
            Swal.close();
        },

        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title:
                    "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
                    error,
            });
        },
    });
}

function formatDate(date) {
    // Convert the input to a Date object if it isn't already
    if (!(date instanceof Date)) {
        date = new Date(date);
    }

    // Check if the date is valid
    if (isNaN(date)) {
        throw new Error("Invalid date");
    }

    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

let tglAwal;
let tglAkhir;

let tahun = $("#tahun").val();
function updateData() {
    reportPendaftaran(tglAwal, tglAkhir);
    reportPendapatanItem(tglAwal, tglAkhir);
    reportKunjungan(tglAwal, tglAkhir);
    reportPendapatanRuang(tglAwal, tglAkhir);
    reportPendapatanTotalPerHari();
}

// function initializeDataTable(selector, titlePrefix, tglAwal, tglAkhir) {
//     $(selector)
//         .DataTable({
//             destroy: true,
//             buttons: [
//                 {
//                     extend: "excelHtml5",
//                     text: "Download",
//                     title: `${titlePrefix} Tanggal: ${tglAwal} s.d. ${tglAkhir}`,
//                     filename: `${titlePrefix} Tanggal: ${tglAwal} s.d. ${tglAkhir}`,
//                 },
//             ],
//         })
//         .buttons()
//         .container()
//         .appendTo(`${selector}_wrapper .col-md-6:eq(0)`);
// }
function initializeDataTable(selector, titlePrefix, tglAwal, tglAkhir) {
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
    }

    $(selector)
        .DataTable({
            destroy: true,
            dom: "Bfrtip",
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Download",
                    title: `${titlePrefix} Tanggal: ${tglAwal} s.d. ${tglAkhir}`,
                    filename: `${titlePrefix} Tanggal: ${tglAwal} s.d. ${tglAkhir}`,
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(`${selector}_wrapper .col-md-6:eq(0)`);
}

window.addEventListener("load", function () {
    setTodayDate();
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);

    tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
    tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

    isiTabelPendapatanTotalPerHari(
        dataSBSU,
        "#tabelPendapatanTotalPerHariUMUM",
        tahun,
        "umum"
    );
    isiTabelPendapatanTotalPerHari(
        dataSBSB,
        "#tabelPendapatanTotalPerHariBPJS",
        tahun,
        "bpjs"
    );

    // Menetapkan nilai ke input tanggal
    $("#reservation, #tglJumlah").val(tglAwal + " to " + tglAkhir);

    // Date range picker
    $("#reservation, #tglJumlah").daterangepicker({
        startDate: tglAwal,
        endDate: tglAkhir,
        autoApply: true,
        locale: {
            format: "YYYY-MM-DD",
            separator: " to ",
            applyLabel: "Apply",
            cancelLabel: "Cancel",
            customRangeLabel: "Custom Range",
        },
    });

    $("#reservation, #tglJumlah").on(
        "apply.daterangepicker",
        function (ev, picker) {
            tglAwal = picker.startDate.format("YYYY-MM-DD");
            tglAkhir = picker.endDate.format("YYYY-MM-DD");

            // Lakukan sesuatu dengan startDate dan endDate
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data...!!!",
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            updateData();
        }
    );
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!",
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    reportPendaftaran(tglAwal, tglAkhir);
    reportKunjungan(tglAwal, tglAkhir);

    initializeDataTable(
        "#tabelPerItemUMUM",
        "Laporan Pendapatan Per Item UMUM",
        tglAwal,
        tglAkhir
    );
    initializeDataTable(
        "#tabelPerItemBPJS",
        "Laporan Pendapatan Per Item BPJS",
        tglAwal,
        tglAkhir
    );

    initializeDataTable(
        "#tabelPerRuangBPJS",
        "Laporan Pendapatan Per Ruang BPJS",
        tglAwal,
        tglAkhir
    );
    initializeDataTable(
        "#tabelPerRuangUMUM",
        "Laporan Pendapatan Per Ruang UMUM",
        tglAwal,
        tglAkhir
    );

    initializeDataTable(
        "#tabelPendapatanTotalPerHariUMUM",
        "Laporan Pendapatan Total UMUM",
        tglAwal,
        tglAkhir
    );
    initializeDataTable(
        "#tabelPendapatanTotalPerHariBPJS",
        "Laporan Pendapatan Total BPJS",
        tglAwal,
        tglAkhir
    );
});
