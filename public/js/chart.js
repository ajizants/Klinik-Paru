let tglAwal = "";
let tglAkhir = "";
var myPieChart;
var chartAvg;
var chartTerlama;

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

function formatDt(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const day = String(d.getDate()).padStart(2, "0");
    const month = String(d.getMonth() + 1).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

function setTabelWaktu(waktu, tglA, tglB) {
    $("#waktuLayanan")
        .DataTable({
            data: waktu,
            columns: [
                { data: "antrean_nomor" },
                { data: "penjamin_nama" },
                { data: "pasien_no_rm" },
                { data: "pasien_nama", className: "col-3" },
                { data: "jenis_kelamin" },
                { data: "pasien_umur" },
                { data: "dokter_nama", className: "col-3" },
                { data: "mulai_panggil", className: "col-2" },
                { data: "tunggu_daftar" },
                { data: "pendaftaran_skip", className: "col-2" },
                { data: "pendaftaran_panggil", className: "col-2" },
                { data: "pendaftaran_selesai", className: "col-2" },
                { data: "waktu_selesai_rm", className: "col-2" },
                { data: "tunggu_rm", className: "col-2" },
                { data: "tunggu_tensi", className: "col-2" },
                { data: "tensi_skip", className: "col-2" },
                { data: "tensi_panggil", className: "col-2" },
                { data: "tensi_selesai", className: "col-2" },
                { data: "lama_tensi", className: "col-2" },
                {
                    data: "durasi_poli",
                    className: "col-2",
                    render: function (data, type, row) {
                        if (data > 90) {
                            return `<span class="p-1 font-weight-bold bg-danger" >${data}</span>`;
                        }
                        return data;
                    },
                },
                { data: "tunggu_poli", className: "col-2" },
                { data: "poli_skip", className: "col-2" },
                { data: "poli_panggil", className: "col-2" },
                { data: "poli_selesai", className: "col-2" },
                { data: "lama_poli", className: "col-2" },
                { data: "tunggu_lab", className: "col-2" },
                { data: "laboratorium_panggil", className: "col-2" },
                { data: "laboratorium_selesai", className: "col-2" },
                // { data: "tunggu_hasil_lab", className: "col-2" },
                {
                    data: "tunggu_hasil_lab",
                    className: "col-2",
                    render: function (data, type, row) {
                        if (data > 120) {
                            return `<span class="p-1 font-weight-bold bg-danger" >${data}</span>`;
                        }
                        return data;
                    },
                },
                { data: "tunggu_ro", className: "col-2" },
                { data: "rontgen_panggil", className: "col-2" },
                { data: "rontgen_selesai", className: "col-2" },
                { data: "tunggu_hasil_ro", className: "col-2" },
                { data: "tunggu_igd", className: "col-2" },
                { data: "igd_panggil", className: "col-2" },
                { data: "igd_selesai", className: "col-2" },
                { data: "lama_igd", className: "col-2" },
                { data: "tunggu_farmasi", className: "col-2" },
                { data: "farmasi_panggil", className: "col-2" },
                { data: "farmasi_selesai", className: "col-2" },
                { data: "tunggu_kasir", className: "col-2" },
                { data: "kasir_panggil", className: "col-2" },
                { data: "kasir_selesai", className: "col-2" },
            ],
            autoWidth: false,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: "Waktu Layanan Tanggal: " + tglA + " s.d. " + tglB,
                    filename:
                        "Waktu Layanan Tanggal: " + tglA + "  s.d. " + tglB,
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
        .appendTo("#waktuLayanan_wrapper .col-md-6:eq(0)");
}

function setTabelRataWaktu(data, tglA, tglB) {
    $("#rataTabel")
        .DataTable({
            //////destroy: true,
            data: [
                {
                    kategori:
                        "Tunggu Daftar, Mulai di panggil sampai selesai di daftar",
                    total_pasien: data.total_pasien,
                    total_waktu: data.total_tunggu_daftar,
                    rata_waktu: data.avg_tunggu_daftar.toFixed(2),
                    waktu_terlama: data.max_tunggu_daftar.toFixed(2),
                    waktu_tercepat: data.min_tunggu_daftar.toFixed(2),
                    background:
                        data.avg_tunggu_daftar > 60
                            ? "red"
                            : data.avg_tunggu_daftar > 0
                            ? "green"
                            : null,
                },
                {
                    kategori:
                        "Tunggu RM, Mulai di panggil sampai selesai RM Siap",
                    total_pasien: data.total_pasien,
                    total_waktu: data.total_tunggu_rm,
                    rata_waktu: data.avg_tunggu_rm.toFixed(2),
                    waktu_terlama: data.max_tunggu_rm.toFixed(2),
                    waktu_tercepat: data.min_tunggu_rm.toFixed(2),
                    background:
                        data.avg_tunggu_rm > 60
                            ? "red"
                            : data.avg_tunggu_rm > 0
                            ? "green"
                            : null,
                },
                {
                    kategori: "Tunggu Tensi",
                    total_pasien: data.total_pasien,
                    total_waktu: data.total_tunggu_tensi,
                    rata_waktu: data.avg_tunggu_tensi.toFixed(2),
                    waktu_terlama: data.max_tunggu_tensi.toFixed(2),
                    waktu_tercepat: data.min_tunggu_tensi.toFixed(2),
                    background:
                        data.avg_tunggu_tensi > 60
                            ? "red"
                            : data.avg_tunggu_tensi > 0
                            ? "green"
                            : null,
                },
                {
                    kategori: "Tunggu Poli",
                    total_pasien: data.total_pasien,
                    total_waktu: data.total_tunggu_poli,
                    rata_waktu: data.avg_tunggu_poli.toFixed(2),
                    waktu_terlama: data.max_tunggu_poli.toFixed(2),
                    waktu_tercepat: data.min_tunggu_poli.toFixed(2),
                    background:
                        data.avg_tunggu_poli > 60
                            ? "red"
                            : data.avg_tunggu_poli > 0
                            ? "green"
                            : null,
                },
                {
                    kategori: "Tunggu Lab",
                    total_pasien: data.total_lab,
                    total_waktu: data.total_tunggu_lab,
                    rata_waktu: data.avg_tunggu_lab.toFixed(2),
                    waktu_terlama: data.max_tunggu_lab.toFixed(2),
                    waktu_tercepat: data.min_tunggu_lab.toFixed(2),
                    background:
                        data.avg_tunggu_lab > 60
                            ? "red"
                            : data.avg_tunggu_lab > 0
                            ? "green"
                            : null,
                },
                {
                    kategori: "Tunggu Hasil Lab",
                    total_pasien: data.total_lab,
                    total_waktu: data.total_tunggu_hasil_lab,
                    rata_waktu: data.avg_tunggu_hasil_lab.toFixed(2),
                    waktu_terlama: data.max_tunggu_hasil_lab.toFixed(2),
                    waktu_tercepat: data.min_tunggu_hasil_lab.toFixed(2),
                    background:
                        data.avg_tunggu_hasil_lab > 120
                            ? "red"
                            : data.avg_tunggu_hasil_lab > 0
                            ? "green"
                            : null,
                },
                {
                    kategori: "Tunggu RO",
                    total_pasien: data.total_ro,
                    total_waktu: data.total_tunggu_ro,
                    rata_waktu: data.avg_tunggu_ro.toFixed(2),
                    waktu_terlama: data.max_tunggu_ro.toFixed(2),
                    waktu_tercepat: data.min_tunggu_ro.toFixed(2),
                    background:
                        data.avg_tunggu_ro > 60
                            ? "red"
                            : data.avg_tunggu_ro > 0
                            ? "green"
                            : null,
                },
                // {
                //     kategori: "Tunggu Hasil RO",
                //     total_pasien: data.total_ro,
                //     total_waktu: data.total_tunggu_hasil_ro,
                //     rata_waktu: data.avg_tunggu_hasil_ro.toFixed(2),
                //     waktu_terlama: data.max_tunggu_hasil_ro.toFixed(2),
                //     waktu_tercepat: data.min_tunggu_hasil_ro.toFixed(2),
                //     background:
                //         data.avg_tunggu_hasil_ro > 60
                //             ? "red"
                //             : data.avg_tunggu_hasil_ro > 0
                //             ? "green"
                //             : null,
                // },
                // {
                //     kategori: "Tunggu IGD",
                //     total_pasien: data.total_igd,
                //     total_waktu: data.total_tunggu_igd,
                //     rata_waktu: data.avg_tunggu_igd.toFixed(2),
                //     waktu_terlama: data.max_tunggu_igd.toFixed(2),
                //     waktu_tercepat: data.min_tunggu_igd.toFixed(2),
                //     background:
                //         data.avg_tunggu_igd > 60
                //             ? "red"
                //             : data.avg_tunggu_igd > 0
                //             ? "green"
                //             : null,
                // },
                {
                    kategori: "Durasi Layanan Tindakan",
                    total_pasien: data.total_igd,
                    total_waktu: data.total_lama_igd,
                    rata_waktu: data.avg_lama_igd.toFixed(2),
                    waktu_terlama: data.max_lama_igd.toFixed(2),
                    waktu_tercepat: data.min_lama_igd.toFixed(2),
                    background:
                        data.avg_lama_igd > 60
                            ? "red"
                            : data.avg_lama_igd > 0
                            ? "green"
                            : null,
                },
                // {
                //     kategori: "Tunggu Farmasi",
                //     total_pasien: data.total_pasien,
                //     total_waktu: data.total_tunggu_farmasi,
                //     rata_waktu: data.avg_tunggu_farmasi.toFixed(2),
                //     waktu_terlama: data.max_tunggu_farmasi.toFixed(2),
                //     waktu_tercepat: data.min_tunggu_farmasi.toFixed(2),
                //     background:
                //         data.avg_tunggu_farmasi > 60
                //             ? "red"
                //             : data.avg_tunggu_farmasi > 0
                //             ? "green"
                //             : null,
                // },
                // {
                //     kategori: "Tunggu Kasir",
                //     total_pasien: data.total_pasien,
                //     total_waktu: data.total_tunggu_kasir,
                //     rata_waktu: data.avg_tunggu_kasir.toFixed(2),
                //     waktu_terlama: data.max_tunggu_kasir.toFixed(2),
                //     waktu_tercepat: data.min_tunggu_kasir.toFixed(2),
                //     background:
                //         data.avg_tunggu_kasir > 60
                //             ? "red"
                //             : data.avg_tunggu_kasir > 0
                //             ? "green"
                //             : null,
                // },
                {
                    kategori:
                        "Durasi Poli dari Pendaftaran selesai sampai dipanggil Poli",
                    total_pasien: data.total_pasien,
                    total_waktu: data.total_durasi_poli,
                    rata_waktu: data.avg_durasi_poli.toFixed(2),
                    waktu_terlama: data.max_durasi_poli.toFixed(2),
                    waktu_tercepat: data.min_durasi_poli.toFixed(2),
                    background:
                        data.avg_durasi_poli > 60
                            ? "red"
                            : data.avg_durasi_poli > 0
                            ? "green"
                            : null,
                },
            ],
            columns: [
                { data: "kategori" },
                { data: "total_pasien" },
                { data: "total_waktu" },
                {
                    data: "rata_waktu",
                    render: function (data, type, row) {
                        const backgroundColor = row.background; // Mengambil nilai warna dari kolom 'background'
                        const textColor =
                            backgroundColor === null ? "black" : "white";
                        return (
                            '<div style="background-color: ' +
                            (backgroundColor || "transparent") +
                            "; color: " +
                            textColor +
                            '; padding: 5px;">' +
                            data +
                            "</div>"
                        );
                    },
                },
                { data: "waktu_terlama" },
                { data: "waktu_tercepat" },
            ],
            order: [[2, "asc"]],
            paging: false,
            info: true,
            responsive: true,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: "Waktu Layanan Tanggal: " + tglA + " s.d. " + tglB,
                    filename:
                        "Waktu Layanan Tanggal: " + tglA + "  s.d. " + tglB,
                },
                // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
            ],
        })
        .buttons()
        .container()
        .appendTo("#rataTabel_wrapper .col-md-6:eq(0)");
}
function setTabelTerlamaWaktu(data, tglA, tglB) {
    $("#terlamaTabel")
        .DataTable({
            data: [
                {
                    kategori:
                        "Tunggu Terlama di Pendaftaran, Mulai di panggil sampai selesai di daftar",
                    waktu_terlama: data.max_tunggu_daftar.toFixed(2),
                    waktu_tercepat: data.min_tunggu_daftar.toFixed(2),
                    background: data.max_tunggu_daftar > 60 ? "red" : "green",
                },
                {
                    kategori:
                        "Tunggu Terlama RM Siap, Mulai di panggil sampai RM Siap",
                    waktu_terlama: data.max_tunggu_rm.toFixed(2),
                    waktu_tercepat: data.min_tunggu_rm.toFixed(2),
                    background: data.max_tunggu_rm > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama di Tensi",
                    waktu_terlama: data.max_tunggu_tensi.toFixed(2),
                    background: data.max_tunggu_tensi > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama di Poli",
                    waktu_terlama: data.max_tunggu_poli.toFixed(2),
                    background: data.max_tunggu_poli > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama di Lab",
                    waktu_terlama: data.max_tunggu_lab.toFixed(2),
                    background: data.max_tunggu_lab > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama Hasil Lab",
                    waktu_terlama: data.max_tunggu_hasil_lab.toFixed(2),
                    background:
                        data.max_tunggu_hasil_lab > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama Hasil RO",
                    waktu_terlama: data.max_tunggu_hasil_ro.toFixed(2),
                    background: data.max_tunggu_hasil_ro > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama di IGD",
                    waktu_terlama: data.max_tunggu_igd.toFixed(2),
                    background: data.max_tunggu_igd > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama di Farmasi",
                    waktu_terlama: data.max_tunggu_farmasi.toFixed(2),
                    background: data.max_tunggu_farmasi > 60 ? "red" : "green",
                },
                {
                    kategori: "Tunggu Terlama di Kasir",
                    waktu_terlama: data.max_tunggu_kasir.toFixed(2),
                    background: data.max_tunggu_kasir > 60 ? "red" : "green",
                },
                {
                    kategori: "Durasi Terlama di Poli dari Pendaftaran",
                    waktu_terlama: data.max_durasi_poli.toFixed(2),
                    background: data.max_durasi_poli > 60 ? "red" : "green",
                },
            ],
            columns: [
                { data: "kategori" },
                { data: "waktu_terlama" },
                {
                    data: "background",
                    render: function (data) {
                        return (
                            '<div style="background-color: ' +
                            data +
                            '; width: 50px; height: 20px;"></div>'
                        );
                    },
                },
            ],
            order: [[1, "dsc"]],
            paging: true,
            searching: false,
            info: true,
            responsive: true,
            pageLength: 7,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"],
            ],
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: "Waktu Layanan Tanggal: " + tglA + " s.d. " + tglB,
                    filename:
                        "Waktu Layanan Tanggal: " + tglA + "  s.d. " + tglB,
                },
                // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
            ],
        })
        .buttons()
        .container()
        .appendTo("#terlamaTabel_wrapper .col-md-6:eq(0)");
}
function setTabelSpmWaktu(data, tglA, tglB) {
    $("#spmTabel")
        .DataTable({
            //////destroy: true, // Hapus tabel yang sudah ada sebelumnya
            data: [
                {
                    kategori:
                        "Waktu Tunggu di Pendaftaran, Mulai di panggil sampai selesai di daftar",
                    jumlah_pasien: data.total_pasien,
                    waktu_lebih: data.lebih_tunggu_daftar,
                    persen_lebih:
                        data.lebih_persen_tunggu_daftar.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_daftar,
                    persen_kurang:
                        data.kurang_persen_tunggu_daftar.toFixed(2) + " %",
                },
                {
                    kategori:
                        "Waktu Tunggu RM Siap, Mulai di panggil pendaftaran sampai RM Siap",
                    jumlah_pasien: data.total_pasien,
                    waktu_lebih: data.lebih_tunggu_rm,
                    persen_lebih: data.lebih_persen_tunggu_rm.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_rm,
                    persen_kurang:
                        data.kurang_persen_tunggu_rm.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu di Tensi",
                    jumlah_pasien: data.total_pasien,
                    waktu_lebih: data.lebih_tunggu_tensi,
                    persen_lebih:
                        data.lebih_persen_tunggu_tensi.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_tensi,
                    persen_kurang:
                        data.kurang_persen_tunggu_tensi.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu di Poli",
                    jumlah_pasien: data.total_pasien,
                    waktu_lebih: data.lebih_tunggu_poli,
                    persen_lebih:
                        data.lebih_persen_tunggu_poli.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_poli,
                    persen_kurang:
                        data.kurang_persen_tunggu_poli.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu di Lab",
                    jumlah_pasien: data.total_lab,
                    waktu_lebih: data.lebih_tunggu_lab,
                    persen_lebih:
                        data.lebih_persen_tunggu_lab.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_lab,
                    persen_kurang:
                        data.kurang_persen_tunggu_lab.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu Hasil Lab",
                    jumlah_pasien: data.total_lab,
                    waktu_lebih: data.lebih_tunggu_hasil_lab,
                    persen_lebih:
                        data.lebih_persen_tunggu_hasil_lab.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_hasil_lab,
                    persen_kurang:
                        data.kurang_persen_tunggu_hasil_lab.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu Hasil RO",
                    jumlah_pasien: data.total_ro,
                    waktu_lebih: data.lebih_tunggu_hasil_ro,
                    persen_lebih:
                        data.lebih_persen_tunggu_hasil_ro.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_hasil_ro,
                    persen_kurang:
                        data.kurang_persen_tunggu_hasil_ro.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu di IGD",
                    jumlah_pasien: data.total_igd,
                    waktu_lebih: data.lebih_tunggu_igd,
                    persen_lebih:
                        data.lebih_persen_tunggu_igd.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_igd,
                    persen_kurang:
                        data.kurang_persen_tunggu_igd.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu di Farmasi",
                    jumlah_pasien: data.total_pasien,
                    waktu_lebih: data.lebih_tunggu_farmasi,
                    persen_lebih:
                        data.lebih_persen_tunggu_farmasi.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_farmasi,
                    persen_kurang:
                        data.kurang_persen_tunggu_farmasi.toFixed(2) + " %",
                },
                {
                    kategori: "Waktu Tunggu di Kasir",
                    jumlah_pasien: data.total_pasien,
                    waktu_lebih: data.lebih_tunggu_kasir,
                    persen_lebih:
                        data.lebih_persen_tunggu_kasir.toFixed(2) + " %",
                    waktu_kurang: data.kurang_tunggu_kasir,
                    persen_kurang:
                        data.kurang_persen_tunggu_kasir.toFixed(2) + " %",
                },
                {
                    kategori:
                        "Waktu Tunggu di Poli, Mulai dari selesai Pendaftaran sampai di panggil Poli (termasuk jika ada pemeriksaan penunjang)",
                    jumlah_pasien: data.total_pasien,
                    waktu_lebih: data.lebih_durasi_poli,
                    persen_lebih:
                        data.lebih_persen_durasi_poli.toFixed(2) + " %",
                    waktu_kurang: data.kurang_durasi_poli,
                    persen_kurang:
                        data.kurang_persen_durasi_poli.toFixed(2) + " %",
                },
            ],
            columns: [
                { data: "kategori" },
                { data: "jumlah_pasien" },
                { data: "waktu_lebih" },
                { data: "persen_lebih" },
                { data: "waktu_kurang" },
                { data: "persen_kurang" },
            ],
            order: [[2, "dsc"]],
            paging: false,
            searching: false,
            info: true,
            responsive: true,
            // pageLength: 11,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"],
            ],
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: "Waktu Layanan Tanggal: " + tglA + " s.d. " + tglB,
                    filename:
                        "Waktu Layanan Tanggal: " + tglA + "  s.d. " + tglB,
                },
                // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
            ],
        })
        .buttons()
        .container()
        .appendTo("#terlamaTabel_wrapper .col-md-6:eq(0)");
}
function setTabelPenunjangWaktu(data, tglA, tglB) {
    $("#penunjangTabel")
        .DataTable({
            //////destroy: true, // Hapus tabel yang sudah ada sebelumnya
            data: [
                {
                    kategori: "Total Pasien",
                    nilai: data.total_pasien,
                },
                {
                    kategori: "Total Pasien Radiologi",
                    nilai: data.total_ro,
                },
                {
                    kategori: "Total Pasien Laboratorium",
                    nilai: data.total_lab,
                },
                {
                    kategori: "Total Pasien Tindakan",
                    nilai: data.total_igd,
                },
                {
                    kategori:
                        "Total Waktu tunggu pendaftaran, pasien ambil nomor sampai di panggil",
                    nilai: data.total_tunggu_daftar.toFixed(2),
                },
                {
                    kategori: "Total Waktu tunggu rekam medis siap",
                    nilai: data.total_tunggu_rm.toFixed(2),
                },
                {
                    kategori: "Total Waktu tunggu hasil Laboratorium",
                    nilai: data.total_tunggu_hasil_lab.toFixed(2),
                },
                {
                    kategori: "Total Waktu tunggu hasil Radiologi",
                    nilai: data.total_tunggu_hasil_ro.toFixed(2),
                },
                {
                    kategori:
                        "Total Waktu tunggu poli, dari pasien mendaftar sampai di panggil poli, termasuk waktu berapa lama pasien di periksa penunjang",
                    nilai: data.total_durasi_poli.toFixed(2),
                },
                {
                    kategori:
                        "Total Waktu tunggu tensi, dari RM siap sampai panggil tensi",
                    nilai: data.total_tunggu_tensi.toFixed(2),
                },
            ],
            columns: [{ data: "kategori" }, { data: "nilai" }],
            order: [[1, "asc"]],
            paging: true,
            searching: false,
            info: true,
            responsive: true,
            pageLength: 7,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"],
            ],
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: "Waktu Layanan Tanggal: " + tglA + " s.d. " + tglB,
                    filename:
                        "Waktu Layanan Tanggal: " + tglA + "  s.d. " + tglB,
                },
                // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
            ],
        })
        .buttons()
        .container()
        .appendTo("#penunjangTabel_wrapper .col-md-6:eq(0)");
}

function ratawaktulayanan(tglAwal, tglAkhir, tanggal, tglA, tglB) {
    var tglA = formatDate(tglAwal);
    var tglB = formatDate(tglAkhir);

    $.ajax({
        url: "/api/kominfo/rata_waktu_tunggu",
        type: "post",
        data: {
            tanggal_awal: tglAwal,
            tanggal_akhir: tglAkhir,
            tanggal: tanggal,
        },
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Data Ditemukan...!!!",
            });
            var data = response.data; // Akses objek 'data' di dalam respons
            var waktu = response.waktu;
            // Masukkan data ke dalam DataTables
            setTabelWaktu(waktu, tglA, tglB);
            setTabelRataWaktu(data, tglA, tglB);
            setTabelTerlamaWaktu(data, tglA, tglB);
            setTabelSpmWaktu(data, tglA, tglB);
            setTabelPenunjangWaktu(data, tglA, tglB);

            // Gambar grafik menggunakan Chart.js seperti sebelumnya
            var ctx = document
                .getElementById("chartPenunjang")
                .getContext("2d");
            if (myPieChart) {
                myPieChart.destroy();
            }
            myPieChart = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: [
                        "Total RO",
                        "Total Lab",
                        "Total IGD",
                        "Tanpa Penunjang",
                    ],
                    datasets: [
                        {
                            label: "Distribution",
                            data: [
                                data.total_ro,
                                data.total_lab,
                                data.total_igd,
                                data.total_tanpa_tambahan,
                            ],
                            backgroundColor: [
                                "rgba(54, 162, 235, 0.5)",
                                "rgba(255, 206, 86, 0.5)",
                                "rgba(75, 192, 192, 0.5)",
                                "rgba(245, 19, 15, 0.5)",
                            ],
                            borderColor: [
                                "rgba(54, 162, 235, 1)",
                                "rgba(255, 206, 86, 1)",
                                "rgba(75, 192, 192, 1)",
                                "rgba(245, 19, 15, 1)",
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "top",
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || "";
                                    if (label) {
                                        label += ": ";
                                    }
                                    if (context.raw !== null) {
                                        label += `${context.raw} (${(
                                            (context.raw / data.total_pasien) *
                                            100
                                        ).toFixed(2)}%)`;
                                    }
                                    return label;
                                },
                            },
                        },
                    },
                },
            });

            // Update total pasien display
            document.getElementById(
                "totalPasien"
            ).innerHTML = `<b>Total Pasien:</b> ${data.total_pasien}`;
            if (chartAvg) {
                chartAvg.destroy();
            }
            var ctx = document.getElementById("chartAvg").getContext("2d");
            chartAvg = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: [
                        "Tunggu Daftar",
                        "Tunggu RM",
                        "Tunggu Tensi",
                        "Tunggu Poli",
                        "Tunggu Lab",
                        "Tunggu Hasil Lab",
                        "Tunggu RO",
                        "Tunggu Hasil RO",
                        "Tunggu IGD",
                        "Tunggu Farmasi",
                        "Tunggu Kasir",
                        "Durasi Poli",
                    ],
                    datasets: [
                        {
                            label:
                                "Rata-rata Waktu Tunggu Dalam Menit " +
                                "(" +
                                tglA +
                                " s.d. " +
                                tglB +
                                ")",
                            data: [
                                data.avg_tunggu_daftar,
                                data.avg_tunggu_rm,
                                data.avg_tunggu_tensi,
                                data.avg_tunggu_poli,
                                data.avg_tunggu_lab,
                                data.avg_tunggu_hasil_lab,
                                data.avg_tunggu_ro,
                                data.avg_tunggu_hasil_ro,
                                data.avg_tunggu_igd,
                                data.avg_tunggu_farmasi,
                                data.avg_tunggu_kasir,
                                data.avg_durasi_poli,
                            ],
                            backgroundColor: [
                                data.avg_tunggu_daftar > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_rm > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_tensi > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_poli > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_lab > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_ro > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_hasil_lab > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_hasil_ro > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",

                                data.avg_tunggu_igd > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_farmasi > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_tunggu_kasir > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.avg_durasi_poli > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                            ],
                            borderColor: [
                                data.avg_tunggu_daftar > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_rm > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_tensi > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_poli > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_lab > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_ro > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_hasil_lab > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_hasil_ro > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_igd > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_farmasi > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_tunggu_kasir > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.avg_durasi_poli > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });

            if (chartTerlama) {
                chartTerlama.destroy();
            }
            var ctx = document.getElementById("chartTerlama").getContext("2d");
            chartTerlama = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: [
                        "Tunggu Daftar",
                        "Tunggu RM",
                        "Tunggu Tensi",
                        "Tunggu Poli",
                        "Tunggu Lab",
                        "Tunggu RO",
                        "Tunggu Hasil Lab",
                        "Tunggu Hasil RO",
                        "Tunggu IGD",
                        "Tunggu Farmasi",
                        "Tunggu Kasir",
                        "Durasi Poli",
                    ],
                    datasets: [
                        {
                            label:
                                "Waktu Tunggu Terlama Dalam Menit " +
                                "(" +
                                tglA +
                                " s.d. " +
                                tglB +
                                ")",
                            data: [
                                data.max_tunggu_daftar,
                                data.max_tunggu_rm,
                                data.max_tunggu_tensi,
                                data.max_tunggu_poli,
                                data.max_tunggu_lab,
                                data.max_tunggu_ro,
                                data.max_tunggu_hasil_lab,
                                data.max_tunggu_hasil_ro,
                                data.max_tunggu_igd,
                                data.max_tunggu_farmasi,
                                data.max_tunggu_kasir,
                                data.max_durasi_poli,
                            ],
                            backgroundColor: [
                                data.max_tunggu_daftar > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_rm > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_tensi > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_poli > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_lab > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_ro > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_hasil_lab > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_hasil_ro > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",

                                data.max_tunggu_igd > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_farmasi > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_tunggu_kasir > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                                data.max_durasi_poli > 60
                                    ? "rgba(255, 99, 132, 0.2)"
                                    : "rgba(54, 162, 235, 0.2)",
                            ],
                            borderColor: [
                                data.max_tunggu_daftar > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_rm > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_tensi > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_poli > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_lab > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_ro > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_hasil_lab > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_hasil_ro > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_igd > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_farmasi > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_tunggu_kasir > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                                data.max_durasi_poli > 60
                                    ? "rgba(255, 99, 132, 1)"
                                    : "rgba(54, 162, 235, 1)",
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        },
        error: function (err, response) {
            console.error("Error fetching data:", err.responseText);
            console.log("🚀 ~ ratawaktulayanan ~ response:", response.error);
            Swal.fire({
                icon: "error",
                title: "Oops...Terjadi kesalahan, silahkan coba lagi!",
                text: err.responseText,
            });
        },
    });
}

function updtWaktuLayanan(tglAwal, tglAkhir, tanggal) {
    tglAwal = formatDt(tglAwal);
    tglAkhir = formatDt(tglAkhir);
    const tglA = formatDate(new Date(tglAwal));
    const tglB = formatDate(new Date(tglAkhir));
    const table = $(
        "#rataTabel, #terlamaTabel, #spmTabel, #penunjangTabel,#waktuLayanan"
    ).DataTable();
    table.clear().draw(); // Clears all data in the table
    table.destroy(); // Destroys the DataTable instance

    Swal.fire({
        icon: "info",
        title:
            "Sedang mencarikan data...!!! \n Dari tanggal:" +
            tglA +
            " s.d. " +
            tglB +
            "\n Pencarian membutuhkan waktu lama jika lebih dari 10 hari, \n Mohon ditunggu...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    ratawaktulayanan(tglAwal, tglAkhir, tanggal, tglA, tglB);
    // waktuLayanan(tglAwal, tglAkhir, tanggal, tglA, tglB);
}

function setTodayDate() {}

window.addEventListener("load", function () {
    // // setTodayDate();
    // var today = new Date().toISOString().split("T")[0];
    // $("#tanggal").val(today);

    tglAkhir = new Date();
    tglAwal = new Date();
    tglAwal.setDate(tglAwal.getDate());
    tglAkhir.setDate(tglAkhir.getDate());

    // Menetapkan nilai ke input tanggal
    tglAwal.value = tglAwal.toISOString().split("T")[0];
    tglAkhir.value = tglAkhir.toISOString().split("T")[0];
    // cariDataLayanan();
    //Date range picker
    $("#ratawaktulayanan").daterangepicker({
        startDate: tglAwal,
        endDate: tglAkhir,
        // autoApply: true, // Apply selection automatically when selecting a date range
        locale: {
            format: "DD-MM-YYYY",
            separator: "  s.d.  ",
            applyLabel: "Cari",
            cancelLabel: "Batal",
            customRangeLabel: "Custom Range",
        },
    });

    $("#ratawaktulayanan").on("apply.daterangepicker", function (ev, picker) {
        tglAwal = picker.startDate.format("YYYY-MM-DD");
        tglAkhir = picker.endDate.format("YYYY-MM-DD");
        // Lakukan sesuatu dengan startDate dan endDate

        updtWaktuLayanan(tglAwal, tglAkhir, tglAkhir);
        // ratawaktulayanan(tglAwal, tglAkhir);
    });

    updtWaktuLayanan(tglAwal, tglAkhir, tglAkhir);
});
