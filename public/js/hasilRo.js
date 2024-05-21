let tglAwal = "";
let tglAkhir = "";

function cariRo(tglAwal, tglAkhir, norm) {
    // var tglAwal = $("#tglAwal").val(); // tambahkan ini
    // var tglAkhir = $("#tglAkhir").val(); // tambahkan ini

    if ($.fn.DataTable.isDataTable("#hasilRo")) {
        var tabletindakan = $("#hasilRo").DataTable();
        tabletindakan.destroy();
    }

    $.ajax({
        url: "/api/hasilRo",
        type: "post",
        data: { norm: norm, tglAkhir: tglAkhir, tglAwal: tglAwal },
        success: function (response) {
            response.data.forEach(function (item, index) {
                item.actions = `<a href="" class="edit"
                                    data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    ><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    data-nama="${item.nama}"><i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
            });

            $("#hasilRo").DataTable({
                data: response.data,
                columns: [
                    { data: "id", className: "p-2" },
                    { data: "tanggal", className: "p-2" },
                    { data: "norm", className: "text-center col-2 p-2" },
                    { data: "nama", className: "p-2" },
                    {
                        data: "actions",
                        className: "text-center p-2",
                    },
                ],
                order: [2, "asc"],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
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
