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
function reportPoin() {
    if ($.fn.DataTable.isDataTable("#report")) {
        var tabletindakan = $("#report").DataTable();
        tabletindakan.clear().destroy();
    }

    var mulaiTgl = $("#mulaiTgl").val(); // Ambil nilai dari input tanggal mulai
    var selesaiTgl = $("#selesaiTgl").val(); // Ambil nilai dari input tanggal selesai
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
                    buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
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

$(document).ready(function () {
    $("#cari").on("click", reportPoin);
    $("#cariAll").on("click", reportPoinAll);

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, "0");
    var mm = String(today.getMonth() + 1).padStart(2, "0"); // January is 0!
    var yyyy = today.getFullYear();

    var formattedDate = yyyy + "-" + mm + "-" + dd;

    $("#mulaiTgl, #mulaiTglAll").val(formattedDate);
    $("#selesaiTgl, #selesaiTglAll").val(formattedDate);

    reportPoin();
    reportPoinAll();
});
