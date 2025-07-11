function reportPoinPetugas() {
    if ($.fn.DataTable.isDataTable("#poinAll")) {
        var tabletindakan = $("#poinAll").DataTable();
        tabletindakan.clear().destroy();
    }

    var mulaiTgl = $("#mulaiTglAll").val(); // Ambil nilai dari input tanggal mulai
    var selesaiTgl = $("#selesaiTglAll").val(); // Ambil nilai dari input tanggal selesai
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });
    const cookiestring = document.cookie.split("=");
    $.ajax({
        url: "/api/poin_kominfo",
        type: "post",
        data: {
            tanggal_awal: mulaiTgl,
            tanggal_akhir: selesaiTgl,
        },
        success: function (response) {
            var data = response.response.data;
            data.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.jumlah = item.jumlah.toLocaleString("id-ID");
                item.nama = item.admin_nama;
                item.tempat = item.ruang_nama;
            });

            $("#poinAll")
                .DataTable({
                    data: data,
                    columns: [
                        { data: "no" },
                        { data: "ruang_nama" },
                        { data: "admin_nama" },
                        { data: "jumlah" },
                    ],
                    order: [0, "asc"],
                    lengthChange: false,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Report Semua Petugas Tanggal: " +
                                mulaiTgl +
                                " s.d. " +
                                selesaiTgl,
                            filename:
                                "Report Semua Petugas Tanggal: " +
                                mulaiTgl +
                                "  s.d. " +
                                selesaiTgl,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#poinAll_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function reportPoinAll() {
    if ($.fn.DataTable.isDataTable("#reportAll")) {
        var tabletindakan = $("#reportAll").DataTable();
        tabletindakan.clear().destroy();
    }

    var mulaiTgl = $("#mulaiTglAll").val(); // Ambil nilai dari input tanggal mulai
    var selesaiTgl = $("#selesaiTglAll").val(); // Ambil nilai dari input tanggal selesai
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });
    const cookiestring = document.cookie.split("=");
    $.ajax({
        url: "/api/cariPoinTotal",
        type: "post",
        data: {
            mulaiTgl: mulaiTgl,
            selesaiTgl: selesaiTgl,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });

            $("#reportAll")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        { data: "nip" },
                        { data: "nama" },
                        { data: "jml" },
                        { data: "sts" },
                    ],
                    order: [0, "asc"],
                    lengthChange: false,
                    autoWidth: false,
                    buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
                })
                .buttons()
                .container()
                .appendTo("#reportAll_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function formatDate(date) {
    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}
function reportPoin() {
    if ($.fn.DataTable.isDataTable("#report")) {
        var tabletindakan = $("#report").DataTable();
        tabletindakan.clear().destroy();
    }

    var mulaiTgl = $("#mulaiTgl").val(); // Ambil nilai dari input tanggal mulai
    var selesaiTgl = $("#selesaiTgl").val(); // Ambil nilai dari input tanggal selesai
    var tglA = formatDate(new Date(mulaiTgl));
    var tglB = formatDate(new Date(selesaiTgl));
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });
    const cookiestring = document.cookie.split("=");
    $.ajax({
        url: "/api/cariPoin",
        type: "post",
        data: {
            mulaiTgl: mulaiTgl,
            selesaiTgl: selesaiTgl,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });

            $("#report")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        { data: "nip" },
                        { data: "nama" },
                        { data: "tindakan" },
                        { data: "jml" },
                        { data: "sts" },
                    ],
                    order: [0, "asc"],
                    lengthChange: false,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Report Petugas IGD Tanggal: \n" +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Report Petugas IGD Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#report_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function reportPoinDots() {
    if ($.fn.DataTable.isDataTable("#reportDots")) {
        var tabletindakan = $("#reportDots").DataTable();
        tabletindakan.clear().destroy();
    }

    var mulaiTgl = $("#mulaiTglDots").val(); // Ambil nilai dari input tanggal mulai
    var selesaiTgl = $("#selesaiTglDots").val(); // Ambil nilai dari input tanggal selesai
    var tglA = formatDate(new Date(mulaiTgl));
    var tglB = formatDate(new Date(selesaiTgl));
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });
    const cookiestring = document.cookie.split("=");
    $.ajax({
        url: "/api/poinDots",
        type: "post",
        data: {
            tglAwal: mulaiTgl,
            tglAkhir: selesaiTgl,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                const lama = item.jumlahLama;
                const baru = item.jumlahBaru;
                item.tampilLama = lama - baru;
                item.tampilBaru = item.jumlahBaru;
                item.tampilInput = item.jumlahLama;
            });

            $("#reportDots")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        // { data: "nip" },
                        { data: "nama" },
                        { data: "tampilInput" },
                        { data: "tampilLama" },
                        { data: "tampilBaru" },
                    ],
                    order: [0, "asc"],
                    lengthChange: false,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Report Petugas Dots Tanggal: \n" +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Report Petugas Dots Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#reportDots_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function reportPoinLoket() {
    if ($.fn.DataTable.isDataTable("#reportLoket")) {
        var tabletindakan = $("#reportLoket").DataTable();
        tabletindakan.clear().destroy();
    }

    var tgl_awal = $("#tgl_awal").val(); // Ambil nilai dari input tanggal mulai
    var tgl_akhir = $("#tgl_akhir").val(); // Ambil nilai dari input tanggal selesai
    var tglA = formatDate(new Date(tgl_awal));
    var tglB = formatDate(new Date(tgl_akhir));
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });
    tampilkanLoading("Sedang mengambil data...");
    const cookiestring = document.cookie.split("=");
    $.ajax({
        url: "/api/get/jumlah_petugas_loket",
        type: "get",
        data: {
            tgl_awal: tgl_awal,
            tgl_akhir: tgl_akhir,
        },
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                const lama = item.jumlahLama;
                const baru = item.jumlahBaru;
                item.total = lama + baru;
            });
            Swal.close();

            $("#reportLoket")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no" },
                        { data: "nama" },
                        { data: "total" },
                        { data: "jumlahLama" },
                        { data: "jumlahBaru" },
                    ],

                    order: [0, "asc"],
                    lengthChange: false,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Report Petugas Loket Tanggal: \n" +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Report Petugas Loket Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#reportLoket_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            tampilkanEror(error + xhr.responseText);
        },
    });
}

$(document).ready(function () {
    $("#cari").on("click", reportPoin);
    $("#cariAll").on("click", reportPoinAll);

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, "0");
    var mm = String(today.getMonth() + 1).padStart(2, "0"); // January is 0!
    var yyyy = today.getFullYear();

    var formattedDate = yyyy + "-" + mm + "-" + dd;

    $("#mulaiTgl, #mulaiTglAll, #mulaiTglDots").val(formattedDate);
    $("#selesaiTgl, #selesaiTglAll, #selesaiTglDots").val(formattedDate);

    reportPoin();
    reportPoinPetugas();
    reportPoinDots();
    CariPoinJaspel();
    reportPoinLoket();
});
