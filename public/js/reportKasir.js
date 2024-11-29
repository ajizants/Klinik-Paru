function cetak(norm) {
    console.log("🚀 ~ cetak ~ norm:", norm);
    // window.open("http://rsparu.kkpm.local/Cetak/RM/norm/" + norm);
    // window.open("http://rsparu.kkpm.local/Cetak/Kartu/norm/" + norm);
    // window.open("http://rsparu.kkpm.local/Cetak/Label/norm/" + norm);
    window.open("http://rsparu.kkpm.local/Cetak/Label3/norm/" + norm);
}

function checkEnter(event) {
    if (event.key === "Enter" || event.keyCode === 13) {
        selesai(); // Call the selesai function when Enter key is pressed
    }
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
                },
                {
                    data: "bayar",
                    title: "Bayar",
                },
                {
                    data: "kembalian",
                    title: "Kembalian",
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

function cetakSBS() {
    const tgl = new Date();
    const tahun = tgl.getFullYear();
    const bulan = String(tgl.getMonth() + 1).padStart(2, "0");
    const tanggal = String(tgl.getDate()).padStart(2, "0");
    const tglSBS = `${tanggal}-${bulan}-${tahun}`;
    console.log("🚀 ~ cetakSBS ~ tglSBS:", tglSBS);

    window.open("api/cetakSBS/" + tglSBS);
}

function isiForm(norm, nama, jaminan, notrans, nosep, btn) {
    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#jaminan").val(jaminan);
    $("#notrans").val(notrans);
    $("#noSep").val(nosep);
    btn.classList.remove("btn-danger");
    btn.classList.add("btn-success");
}

function segarkan() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
        showConfirmButton: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    reportPendaftaran(tglAwal, tglAkhir);
}
function cariJumlah() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
        showConfirmButton: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    reportJumlah(tglAwal, tglAkhir);
}

function reportPendaftaran(tglAwal, tglAkhir) {
    reportKunjungan(tglAwal, tglAkhir);
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

var tglAwal;
var tglAkhir;

window.addEventListener("load", function () {
    setTodayDate();
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);

    // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
    // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
    tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
    tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

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
            reportPendaftaran(tglAwal, tglAkhir);
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

    // setInterval(function () {
    //     reportPendaftaran(tglAwal, tglAkhir);
    // }, 60000);
    $("#modalSep").on("shown.bs.modal", function () {
        $("#noSep").focus();
    });
});
