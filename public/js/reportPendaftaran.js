function cetak(norm) {
    console.log("🚀 ~ cetak ~ norm:", norm);
    // window.open("http://rsparu.kkpm.local/Cetak/RM/norm/" + norm);
    // window.open("http://rsparu.kkpm.local/Cetak/Kartu/norm/" + norm);
    window.open("http://rsparu.kkpm.local/Cetak/Label/norm/" + norm);
    window.open("http://rsparu.kkpm.local/Cetak/Label2/norm/" + norm);
}

function checkEnter(event) {
    if (event.key === "Enter" || event.keyCode === 13) {
        selesai(); // Call the selesai function when Enter key is pressed
    }
}

function selesai(norm, notrans) {
    var norm = norm ? norm : $("#norm").val();
    var notrans = notrans ? notrans : $("#notrans").val();
    var nosep = $("#noSep").val();
    if (!norm) {
        Toast.fire({
            icon: "error",
            title: "Belum Ada Data Transaksi...!!! ",
        });
    } else {
        $.ajax({
            url: "/api/pendaftaran/selesai",
            type: "post",
            data: {
                norm: norm,
                notrans: notrans,
                nosep: nosep,
            },
            success: function (response) {
                Swal.fire({
                    icon: "info",
                    title: response.message + "Sedang memperbarui data...!!!",
                    showConfirmButton: true,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
                reportPendaftaran(tglAwal, tglAkhir);
                document.getElementById("formSep").reset();
                $("#modalSep").modal("hide");
            },
        });
    }
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
                item.aksi = `<button type="button" class="btn btn-primary mr-2"
                                    onclick="cetak('${item.pasien_no_rm}')" placeholder="Cetak"><i class="fa-solid fa-print"></i></button>
                            <button type="button" class="btn btn-${item.check_in}" id="checkin" placeholder="Selesai" data-toggle="modal"
                                    data-target="#modalSep" onclick="isiForm('${item.pasien_no_rm}', '${item.pasien_nama}','${item.penjamin_nama}','${item.no_trans}','${item.no_sep}',this)"><i class="fa-regular fa-square-check"></i></button>`;
                if (item.check_in == "danger") {
                    item.status = "Belum";
                } else {
                    item.status = "Selesai";
                }
            });

            $("#report")
                .DataTable({
                    data: pendaftaran,
                    columns: [
                        { data: "aksi", className: "px-0 col-3" },
                        { data: "antrean_nomor" },
                        { data: "tanggal" },
                        { data: "no_sep" },
                        { data: "penjamin_nama" },
                        { data: "daftar_by" },
                        { data: "pasien_lama_baru" },
                        { data: "pasien_no_rm" },
                        { data: "pasien_nama", className: "col-2" },
                        { data: "jenis_kelamin_nama" },
                        { data: "pasien_umur" },
                        { data: "pasien_alamat", className: "col-3" },
                        { data: "poli_nama" },
                        { data: "dokter_nama", className: "col-3" },
                        {
                            data: "status",
                            render: function (data) {
                                const statusClasses = {
                                    Belum: "danger",
                                    Selesai: "success",
                                    default: "secondary",
                                };
                                return `<div class="badge badge-${
                                    statusClasses[data] || statusClasses.default
                                }">${data}</div>`;
                            },
                        },
                    ],
                    autoWidth: false,
                    order: [
                        [14, "asc"],
                        [1, "asc"],
                    ],
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
                .appendTo("#report_wrapper .col-md-6:eq(0)");
            $("#total")
                .DataTable({
                    data: [total],
                    columns: [
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
function reportPendaftaranold(tglAwal, tglAkhir) {
    var tglA = formatDate(new Date(tglAwal));
    var tglB = formatDate(new Date(tglAkhir));

    if ($.fn.DataTable.isDataTable("#report")) {
        var tabletindakan = $("#report").DataTable();
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
            console.log("🚀 ~ reportPendaftaran ~ total:", total);
            console.log("🚀 ~ reportPendaftaran ~ $data:", pendaftaran);
            $("#report")
                .DataTable({
                    data: pendaftaran,
                    columns: [
                        { data: "antrean_nomor" },
                        { data: "tanggal" },
                        { data: "penjamin_nama" },
                        { data: "daftar_by" },
                        { data: "pasien_lama_baru" },
                        { data: "pasien_no_rm" },
                        { data: "pasien_nama", className: "col-3" },
                        { data: "jenis_kelamin_nama" },
                        { data: "pasien_umur" },
                        { data: "poli_nama" },
                        { data: "dokter_nama", className: "col-3" },
                        // { data: "alamat", className: "col-3" },
                    ],
                    autoWidth: false,
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
                .appendTo("#report_wrapper .col-md-6:eq(0)");
            $("#total")
                .DataTable({
                    data: total,
                    columns: [
                        { data: "jumlah_pasien" },
                        { data: "jumlah_pasien_batal" },
                        { data: "jumlah_nomor_skip" },
                        { data: "jumlah_BPJS" },
                        { data: "jumlah_UMUM" },
                        { data: "jumlah_pasien_LAMA" },
                        { data: "jumlah_pasien_BARU" },
                        { data: "jumlah_daftar_OTS" },
                        { data: "jumlah_daftar_JKN" },
                    ],
                    autoWidth: false,
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
            // $("#total")
            //     .DataTable({
            //         destroy: true, // Hapus tabel yang sudah ada sebelumnya
            //         data: [
            //             {
            //                 kategori: "Jumlah Pasien",
            //                 jumlah: total.jumlah_pasien.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah Pasien Batal",
            //                 jumlah: total.jumlah_pasien_batal.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah nomor di Lewati",
            //                 jumlah: total.jumlah_nomor_skip.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah Pasien BPJS",
            //                 jumlah: total.jumlah_BPJS.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah Pasien UMUM",
            //                 jumlah: total.jumlah_UMUM.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah Pasien LAMA",
            //                 jumlah: total.jumlah_pasien_LAMA.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah Pasien BARU",
            //                 jumlah: total.jumlah_pasien_BARU.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah Daftar Lewat JKN",
            //                 jumlah: total.jumlah_daftar_JKN.toFixed(0),
            //             },
            //             {
            //                 kategori: "Jumlah Daftar OTS",
            //                 jumlah: total.jumlah_daftar_OTS.toFixed(0),
            //             },
            //         ],
            //         columns: [
            //             {
            //                 data: null,
            //                 render: function (data, type, row, meta) {
            //                     return meta.row + 1; // Menambahkan nomor urut
            //                 },
            //                 title: "No",
            //             },
            //             { data: "kategori" },
            //             { data: "jumlah" },
            //         ],
            //         autoWidth: false,
            //         buttons: [
            //             {
            //                 extend: "excelHtml5",
            //                 text: "Excel",
            //                 title:
            //                     "Laporan Jumlah Pendaftaran Tanggal: " +
            //                     tglA +
            //                     " s.d. " +
            //                     tglB,
            //                 filename:
            //                     "Laporan Jumlah Pendaftaran Tanggal: " +
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
            //     .appendTo("#total_wrapper .col-md-6:eq(0)");
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
    $("#modalSep").on("shown.bs.modal", function () {
        $("#noSep").focus();
    });
});
