function formatTgl(date) {
    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}
function cariRo(tglAwal, tglAkhir, cetak, norm) {
    var tglA = formatTgl(new Date(tglAwal));
    var tglB = formatTgl(new Date(tglAkhir));
    var petugas = $("#petugas").val();
    if (cetak === "cetak") {
        if (!petugas || petugas.trim() === "") {
            tampilkanEror("Harap pilih petugas terlebih dahulu!");
            return;
        }
        if (cetak === "cetak") {
            if (!petugas || petugas.trim() === "") {
                tampilkanEror("Harap pilih petugas terlebih dahulu!");
                return;
            }

            // Buat URL dengan parameter GET
            var url = `/api/logBook?tglAwal=${encodeURIComponent(
                tglAwal
            )}&tglAkhir=${encodeURIComponent(
                tglAkhir
            )}&cetak=${encodeURIComponent(cetak)}&petugas=${encodeURIComponent(
                petugas
            )}`;

            window.open(url, "_blank");
        }
    }

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
        data: {
            norm: norm,
            tglAkhir: tglAkhir,
            tglAwal: tglAwal,
            cetak: cetak,
            petugas: petugas,
        },
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Data Log Book Ditemukan...!!!",
            });
            const data = response.data;
            document.getElementById("containerTableLogBook").innerHTML = data;
            $("#logBookTable")
                .DataTable({
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
                    error,
            });
        },
    });
}
function cariKegiatanRo(tglAwal, tglAkhir) {
    console.log("🚀 ~ cariKegiatanRo ~ tglAkhir:", tglAkhir);
    console.log("🚀 ~ cariKegiatanRo ~ tglAwal:", tglAwal);
    const url = `/api/ro/kegiatan/laporan/` + tglAwal + `/` + tglAkhir;
    console.log("🚀 ~ cariKegiatanRo ~ url:", url);
    //open url blnk
    window.open(url, "_blank");
}

function tabelRo() {}

let tglAwal;
let tglAkhir;

document.addEventListener("DOMContentLoaded", function () {
    // Mengatur nilai awal tanggal ke hari ini menggunakan variabel biasa
    let dateNow = new Date();

    // Mengonversi tanggal ke format "YYYY-MM-DD"
    tglAwal = dateNow.toISOString().split("T")[0];
    tglAkhir = dateNow.toISOString().split("T")[0];

    console.log("🚀 ~ tglAkhir:", tglAkhir);
    console.log("🚀 ~ tglAwal:", tglAwal);

    // Menangani pencarian berdasarkan NORM dengan tombol enter
    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            cariRo(); // Panggil fungsi cariRo() sesuai kebutuhan
        }
    });

    // Inisialisasi Date Range Picker
    $("#reservation").daterangepicker();
    $("#reservation").on("apply.daterangepicker", function (ev, picker) {
        // Menangkap startDate dan endDate dari picker
        tglAwal = picker.startDate.format("YYYY-MM-DD");
        tglAkhir = picker.endDate.format("YYYY-MM-DD");

        var norm = $("#norm").val();
        // Lakukan sesuatu dengan startDate dan endDate
        console.log("Start Date: " + tglAwal);
        console.log("End Date: " + tglAkhir);

        // Optional: Panggil cariRo() dengan parameter yang diperlukan
        // cariRo(tglAwalFormatted, tglAkhirFormatted, norm);
    });
});
