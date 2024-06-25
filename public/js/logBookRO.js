let tglAwal = "";
let tglAkhir = "";

function formatDate(date) {
    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}
function cariRo(tglAwal, tglAkhir, norm) {
    // var tglAwal = $("#tglAwal").val(); // tambahkan ini
    // var tglAkhir = $("#tglAkhir").val(); // tambahkan ini
    var tglA = formatDate(new Date(tglAwal));
    var tglB = formatDate(new Date(tglAkhir));
    if ($.fn.DataTable.isDataTable("#hasilRo")) {
        var tabletindakan = $("#hasilRo").DataTable();
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
            response.data.forEach(function (item, index) {
                item.actions = `<a href="" class="edit"
                                    data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    ><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    data-nama="${item.nama}"><i class="fas fa-trash"></i></a>`;
            });

            $("#hasilRo")
                .DataTable({
                    data: response.data,
                    columns: [
                        {
                            data: null, // Data null akan diisi oleh render function
                            render: function (data, type, row, meta) {
                                return meta.row + 1; // Nomor urut mulai dari 1
                            },
                            title: "No", // Judul kolom
                        },
                        { data: "noreg" },
                        { data: "tgltrans" },
                        { data: "norm" },
                        { data: "nama" },
                        { data: "layanan" },
                        { data: "jkel" },
                        { data: "alamatDbOld" },
                        { data: "nmFoto" },
                        { data: "ukuranFilm" },
                        { data: "kondisiRo" },
                        { data: "jmlFilmDipakai" },
                        { data: "jmlExpose" },
                        { data: "jmlFilmRusak" },
                        { data: "proyeksi" },
                        { data: "nmMesin" },
                        { data: "catatan" },
                        { data: "radiografer_nama" },
                    ],
                    // order: [
                    //     [1, "asc"],
                    //     [0, "asc"],
                    // ],
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
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#hasilRo_wrapper .col-md-6:eq(0)");
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
