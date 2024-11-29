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
            // Directly using response since it is already the array
            response.forEach(function (item, index) {
                item.aksi = `
                    <a type="button" class="btn btn-sm btn-warning mr-2 mb-2" placeholder="Resume"
                    data-nomor="${item.nomor}"                                
                    data-tgl_nomor="${item.tgl_nomor}"                                
                    data-hari="${item.hari}"                                
                    data-tgl_pendapatan="${item.tgl_pendapatan}"                                
                    data-tgl_setor="${item.tgl_setor}"                                
                    data-jumlah="${item.jumlah}"                                
                    data-jumlah2="${item.jumlah2}"                                
                    >Cetak Bukti Setoran</a>
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
                        { data: "pendapatan" },
                        // { data: "pendapatan" },
                        {
                            data: "pendapatan",
                            render: function (data, type, row) {
                                var formattedTarif = parseInt(
                                    data
                                ).toLocaleString("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                });
                                return `${formattedTarif}
                                    
                                    <input type="hidden" value="${data}">
                                `;
                            },
                        },
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
                            filename: "Laporan Pendaftaran Tanggal: " + tahun,
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

function cetakBAPH() {
    const tgl = new Date();
    const tahun = tgl.getFullYear();
    const bulan = String(tgl.getMonth() + 1).padStart(2, "0");
    const tanggal = String(tgl.getDate()).padStart(2, "0");
    const tglSBS = `${tanggal}-${bulan}-${tahun}`;
    console.log("🚀 ~ cetakSBS ~ tglSBS:", tglSBS);

    window.open("api/cetakSBS/" + tglSBS);
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
