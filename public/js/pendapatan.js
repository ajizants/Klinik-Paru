function report() {
    if ($.fn.DataTable.isDataTable("#report")) {
        var tabel = $("#report").DataTable();
        tabel.clear().destroy();
    }
    const tahun = $("#tahun").val();
    console.log("🚀 ~ report ~ tahun:", tahun);
    $.ajax({
        url: "/api/pendapatan/" + tahun,
        type: "get",
        success: function (response) {
            Swal.close();
            response.forEach(function (item, index) {
                item.aksi = `
                    <a type="button" class="btn btn-sm btn-warning mr-2 mb-2" placeholder="Resume"
                        data-nomor="${item.nomor}"
                        data-tgl_nomor="${item.tgl_nomor}"
                        data-hari="${item.hari}"
                        data-tgl_pendapatan="${item.tgl_pendapatan}"
                        data-tgl_setor="${item.tgl_setor}"
                        data-jumlah="${item.jumlah}"
                        data-terbilang="${item.terbilang}"
                        href="/api/cetakBAPH/${item.tanggal}/${tahun}" target="_blank">Cetak BAPH</a>
                    <a type="button" class="btn btn-sm btn-success mr-2 mb-2" placeholder="Resume"
                        data-nomor="${item.nomor}"
                        data-tgl_nomor="${item.tgl_nomor}"
                        data-hari="${item.hari}"
                        data-tgl_pendapatan="${item.tgl_pendapatan}"
                        data-tgl_setor="${item.tgl_setor}"
                        data-jumlah="${item.jumlah}"
                        data-terbilang="${item.terbilang}"
                        href="/api/cetakBAPH/${item.tanggal}/${tahun}" target="_blank">Cetak SBS</a>
                `;
                item.no = index + 1;
            });

            $("#report")
                .DataTable({
                    data: response, // Use response directly here
                    columns: [
                        { data: "aksi" },
                        { data: "no" },
                        { data: "tanggal" },
                        { data: "nomor" },
                        { data: "kode_akun" },
                        { data: "uraian" },
                        { data: "jumlah" },
                        { data: "pendapatan" },
                    ],
                    autoWidth: false,
                    order: [
                        [1, "asc"], // Adjusted column index for ordering
                    ],
                    buttons: [
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Laporan Pendapatan Tahun: " + tahun,
                            filename: "Laporan Pendaftaran Tahun: " + tahun,
                        },
                        {
                            extend: "colvis",
                            text: "Tampilkan Kolom",
                        },
                    ],
                })
                .buttons()
                .container()
                .appendTo("#report_wrapper .col-md-6:eq(0)");
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

var tglAwal;
var tglAkhir;

window.addEventListener("load", function () {
    setTodayDate();
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);
    report();
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!",
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
});
