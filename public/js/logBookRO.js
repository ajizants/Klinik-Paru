let tglAwal = "";
let tglAkhir = "";

function formatTgl(date) {
    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}
function cariRo(tglAwal, tglAkhir, norm) {
    // var tglAwal = $("#tglAwal").val(); // tambahkan ini
    // var tglAkhir = $("#tglAkhir").val(); // tambahkan ini
    var tglA = formatTgl(new Date(tglAwal));
    var tglB = formatTgl(new Date(tglAkhir));
    if ($.fn.DataTable.isDataTable("#hasilRo, #jumlahPetugas")) {
        var tabletindakan = $("#hasilRo, #jumlahPetugas").DataTable();
        tabletindakan.destroy();
    }

    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan Log Book...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    $.ajax({
        url: "/api/logBook",
        type: "post",
        data: { norm: norm, tglAkhir: tglAkhir, tglAwal: tglAwal },
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Data Log Book Ditemukan...!!!",
            });
            const data = response.data;
            document.getElementById("containerTableLogBook").innerHTML = data;
            $("#logBookTable")
                .DataTable({
                    // data: response.data,
                    // columns: [
                    //     {
                    //         data: null, // Data null akan diisi oleh render function
                    //         render: function (data, type, row, meta) {
                    //             return meta.row + 1; // Nomor urut mulai dari 1
                    //         },
                    //         title: "No", // Judul kolom
                    //     },
                    //     { data: "noreg" },
                    //     { data: "tgltrans" },
                    //     { data: "norm" },
                    //     { data: "nama" },
                    //     { data: "layanan" },
                    //     { data: "jkel" },
                    //     { data: "alamatDbOld", className: "col-4" },
                    //     { data: "nmFoto" },
                    //     { data: "ukuranFilm" },
                    //     { data: "kondisiRo" },
                    //     { data: "jmlFilmDipakai" },
                    //     { data: "jmlExpose" },
                    //     { data: "jmlFilmRusak" },
                    //     { data: "proyeksi" },
                    //     { data: "nmMesin" },
                    //     { data: "catatan" },
                    //     { data: "radiografer_nama" },
                    // ],
                    autoWidth: false,
                    paging: true,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Log Book Radiologi Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Log Book Radiologi Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        "colvis",
                    ],
                })
                .buttons()
                .container()
                .appendTo("#logBookTable_wrapper .col-md-6:eq(0)");

            $("#jumlahPetugas")
                .DataTable({
                    data: response.jumlah,
                    columns: [
                        {
                            data: null, // Data null akan diisi oleh render function
                            render: function (data, type, row, meta) {
                                return meta.row + 1; // Nomor urut mulai dari 1
                            },
                            title: "No", // Judul kolom
                        },
                        { data: "nip" },
                        { data: "nama" },
                        { data: "jml" },
                    ],
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
                                "Log Book Radiologi Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Log Book Radiologi Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        "colvis",
                    ],
                })
                .buttons()
                .container()
                .appendTo("#jumlahPetugas_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.log("🚀 ~ cariRo ~ status:", status);
            console.log("🚀 ~ cariRo ~ xhr:", xhr.responseJSON);
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title:
                    "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
                    xhr.responseJSON.message,
            });
        },
    });
}

function cariRo2() {
    var tglAwal = document.getElementById("tglAwal").value;
    var tglAkhir = document.getElementById("tglAkhir").value;
    var norm = document.getElementById("norm").value;

    // Data yang akan dikirimkan dalam permintaan
    var data = {
        tglAwal: tglAwal,
        tglAkhir: tglAkhir,
        norm: norm,
    };

    // Konfigurasi permintaan
    var requestOptions = {
        method: "POST", // Anda dapat mengubah metode HTTP sesuai kebutuhan
        headers: {
            "Content-Type": "application/json", // Pastikan Anda mengatur header dengan benar
        },
        body: JSON.stringify(data),
    };

    // Kirim permintaan ke URL /api/hasilRo
    fetch("/api/hasilRo", requestOptions)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Terjadi kesalahan saat mengambil data");
            }
            return response.json();
        })
        .then((data) => {
            // Kosongkan isi tabel sebelum menambahkan data baru
            $("#hasilRo").DataTable().clear().draw();

            // Iterasi melalui data dan tambahkan baris baru ke dalam tabel
            data.forEach((item) => {
                $("#hasilRo")
                    .DataTable()
                    .row.add([
                        item.id, // Data ID
                        item.norm, // Data NORM
                        item.tanggal, // Data tanggal
                        item.nama, // Data nama
                        // Tambahkan kolom sesuai dengan data yang diterima dari permintaan AJAX
                    ])
                    .draw();
            });
        })
        .catch((error) => {
            // Tangani kesalahan
            console.error("Error:", error.message);
        });
}

function tabelRo() {}
window.addEventListener("load", function () {
    let today = new Date();
    tglAkhir.value = today.toISOString().split("T")[0];
    tglAwal.value = today.toISOString().split("T")[0];

    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            cariRo();
        }
    });

    //Date range picker
    $("#reservation").daterangepicker();
    $("#reservation").on("apply.daterangepicker", function (ev, picker) {
        tglAwal = picker.startDate.format("YYYY-MM-DD");
        tglAkhir = picker.endDate.format("YYYY-MM-DD");
        var norm = $("#norm").val();
        // Lakukan sesuatu dengan startDate dan endDate
        console.log("Start Date: " + tglAwal);
        console.log("End Date: " + tglAkhir);
        cariRo(tglAwal, tglAkhir, norm);
    });
});
