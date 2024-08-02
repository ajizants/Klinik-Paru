function getChartData() {
    // Mengambil tahun dari input
    var selectedYear = $("#year-selector").val();

    // Mengambil data dari endpoint 'chart' menggunakan AJAX dengan tahun sebagai parameter
    $.ajax({
        url: "/api/chart",
        method: "GET",
        data: {
            year: selectedYear, // Mengirim tahun sebagai parameter
        },
        success: function (response) {
            var data = response;

            var selectedYear = $("#year-selector").val();
            console.log("🚀 ~ getChartData ~ selectedYear:", selectedYear);
            drawChart(data, selectedYear);
            tabelIgd(data, selectedYear);
        },
    });
}
var myChart;

function drawChart(data, tahun) {
    // Data untuk dataset 'umum' dan 'bpjs'
    var umumData = Array(12).fill(0); // Inisialisasi array dengan 12 elemen nol
    var bpjsData = Array(12).fill(0);
    var bulanLabels = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];

    var canvas = document.getElementById("myAreaChart");
    var cardBody = document.querySelector(".card-body");

    // Mengatur lebar dan tinggi canvas sesuai dengan lebar dan tinggi card-body
    canvas.width = cardBody.offsetWidth;
    canvas.height = cardBody.offsetHeight;

    // Mengisi data bulan dengan nilai dari respons JSON
    data.forEach(function (item) {
        var bulanIndex = item.bulan - 1; // Mengonversi nilai bulan ke indeks array (dikurangi 1 karena indeks dimulai dari 0)
        var kelompok = item.kelompok.toLowerCase();
        if (kelompok === "umum") {
            umumData[bulanIndex] = item.jumlah;
        } else if (kelompok === "bpjs") {
            bpjsData[bulanIndex] = item.jumlah;
        }
    });

    // Pengaturan Grafik
    var options = {
        responsive: true,
        maintainAspectRatio: false, // Ini akan membuat chart menyesuaikan dengan ukuran canvas
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    };

    // Menggambar Grafik menggunakan Chart.js
    var ctx = canvas.getContext("2d");
    if (myChart) {
        myChart.destroy();
    }
    myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: "Umum",
                    data: umumData,
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                },
                {
                    label: "BPJS",
                    data: bpjsData,
                    backgroundColor: "rgba(255, 99, 132, 0.2)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1,
                },
            ],
        },
        options: options,
    });

    // Menyesuaikan tinggi canvas agar sesuai dengan ukuran card-body
    window.addEventListener("resize", function () {
        canvas.width = cardBody.offsetWidth;
        canvas.height = cardBody.offsetHeight;
        myChart.resize();
    });
}

function tabelIgd(data, tahun) {
    // Array untuk nama-nama bulan
    var namaBulan = [
        "Januari - " + tahun,
        "Februari - " + tahun,
        "Maret - " + tahun,
        "April - " + tahun,
        "Mei - " + tahun,
        "Juni - " + tahun,
        "Juli - " + tahun,
        "Agustus - " + tahun,
        "September - " + tahun,
        "Oktober - " + tahun,
        "November - " + tahun,
        "Desember - " + tahun,
    ];

    // Menginisialisasi DataTable dengan data yang diberikan
    $("#tabelIgd")
        .DataTable({
            destroy: true, // Menghapus tabel yang sudah ada sebelumnya
            data: data,
            columns: [
                { data: "bulan" },
                {
                    data: "bulan",
                    render: function (data) {
                        // Mengembalikan nama bulan berdasarkan indeks bulan (0 sampai 11)
                        return namaBulan[data - 1]; // Mengurangi 1 karena indeks dimulai dari 0
                    },
                },
                { data: "kelompok" },
                { data: "jumlah" },
            ],
            order: [[0, "asc"]], // Urutan berdasarkan kolom pertama (bulan) secara ascending
            paging: true,
            searching: false,
            info: true,
            pageLength: 5,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"],
            ],
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: "Data Kunjungan IGD Tahun: " + tahun,
                    filename: "Data Kunjungan IGD Tahun: " + tahun,
                },
                // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
            ],
        })
        .buttons()
        .container()
        .appendTo("#tabelIgd_wrapper .col-md-6:eq(0)");
}

$("#year-selector").on("change", function () {
    getChartData();
});

let tglAwal = "";
let tglAkhir = "";

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

function waktuLayanan(tglAwal, tglAkhir, tanggal) {
    // var tglAwal = $("#tglAwal").val(); // tambahkan ini
    // var tglAkhir = $("#tglAkhir").val(); // tambahkan ini
    var tglA = formatDate(new Date(tglAwal));
    var tglB = formatDate(new Date(tglAkhir));
    if ($.fn.DataTable.isDataTable("#waktuLayanan")) {
        var tabletindakan = $("#waktuLayanan").DataTable();
        tabletindakan.destroy();
    }

    $.ajax({
        url: "/api/kominfo/waktu_layanan",
        type: "post",
        data: {
            tanggal_awal: tglAwal,
            tanggal_akhir: tglAkhir,
            tanggal: tanggal,
        },
        success: function (response) {
            $("#waktuLayanan")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "antrean_nomor" },
                        // { data: "tanggal" },
                        { data: "penjamin_nama" },
                        // { data: "daftar_by" },
                        // { data: "status_pasien" },
                        { data: "pasien_no_rm" },
                        { data: "pasien_nama", className: "col-3" },
                        { data: "jenis_kelamin" },
                        { data: "pasien_umur" },
                        // { data: "poli_nama" },
                        { data: "dokter_nama", className: "col-3" },
                        { data: "mulai_panggil", className: "col-2" },
                        { data: "tunggu_daftar" },
                        { data: "pendaftaran_panggil", className: "col-2" },
                        { data: "pendaftaran_selesai", className: "col-2" },
                        { data: "waktu_selesai_rm", className: "col-2" },
                        { data: "tunggu_rm", className: "col-2" },
                        { data: "tunggu_tensi", className: "col-2" },
                        { data: "tensi_skip", className: "col-2" },
                        { data: "tensi_panggil", className: "col-2" },
                        { data: "tensi_selesai", className: "col-2" },
                        { data: "lama_tensi", className: "col-2" },
                        { data: "tunggu_poli", className: "col-2" },
                        { data: "poli_skip", className: "col-2" },
                        { data: "poli_panggil", className: "col-2" },
                        { data: "poli_selesai", className: "col-2" },
                        { data: "lama_poli", className: "col-2" },
                        { data: "tunggu_lab", className: "col-2" },
                        // { data: "laboratorium_skip", className: "col-2" },
                        { data: "laboratorium_panggil", className: "col-2" },
                        { data: "laboratorium_selesai", className: "col-2" },
                        { data: "tunggu_hasil_lab", className: "col-2" },
                        { data: "tunggu_ro", className: "col-2" },
                        // { data: "rontgen_skip", className: "col-2" },
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
                            title:
                                "Waktu Layanan Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Waktu Layanan Tanggal: " +
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
                .appendTo("#waktuLayanan_wrapper .col-md-6:eq(0)");
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

var myPieChart;
var chartAvg;
var chartTerlama;

function ratawaktulayanan(tglAwal, tglAkhir, tanggal) {
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
            var data = response.data; // Akses objek 'data' di dalam respons

            // Masukkan data ke dalam DataTables
            $("#rataTabel")
                .DataTable({
                    destroy: true, // Hapus tabel yang sudah ada sebelumnya
                    data: [
                        {
                            kategori:
                                "Tunggu Daftar, Mulai di panggil sampai selesai di daftar",
                            rata_waktu: data.avg_tunggu_daftar.toFixed(2),
                            background:
                                data.avg_tunggu_daftar > 60 ? "red" : "green",
                        },
                        {
                            kategori:
                                "Tunggu RM, Mulai di panggil sampai selesai RM Siap",
                            rata_waktu: data.avg_tunggu_rm.toFixed(2),
                            background:
                                data.avg_tunggu_rm > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Tensi",
                            rata_waktu: data.avg_tunggu_tensi.toFixed(2),
                            background:
                                data.avg_tunggu_tensi > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Poli",
                            rata_waktu: data.avg_tunggu_poli.toFixed(2),
                            background:
                                data.avg_tunggu_poli > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Lab",
                            rata_waktu: data.avg_tunggu_lab.toFixed(2),
                            background:
                                data.avg_tunggu_lab > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Hasil Lab",
                            rata_waktu: data.avg_tunggu_hasil_lab.toFixed(2),
                            background:
                                data.avg_tunggu_hasil_lab > 60
                                    ? "red"
                                    : "green",
                        },
                        {
                            kategori: "Tunggu RO",
                            rata_waktu: data.avg_tunggu_ro.toFixed(2),
                            background:
                                data.avg_tunggu_ro > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Hasil RO",
                            rata_waktu: data.avg_tunggu_hasil_ro.toFixed(2),
                            background:
                                data.avg_tunggu_hasil_ro > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu IGD",
                            rata_waktu: data.avg_tunggu_igd.toFixed(2),
                            background:
                                data.avg_tunggu_igd > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Farmasi",
                            rata_waktu: data.avg_tunggu_farmasi.toFixed(2),
                            background:
                                data.avg_tunggu_farmasi > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Kasir",
                            rata_waktu: data.avg_tunggu_kasir.toFixed(2),
                            background:
                                data.avg_tunggu_kasir > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Durasi Poli dari Pendaftaran",
                            rata_waktu: data.avg_durasi_poli.toFixed(2),
                            background:
                                data.avg_durasi_poli > 60 ? "red" : "green",
                        },
                    ],
                    columns: [
                        { data: "kategori" },
                        { data: "rata_waktu" },
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
                    order: [[1, "dsc"]], // Urutkan berdasarkan kolom kedua (rata_waktu) secara ascending
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
                            title:
                                "Waktu Layanan Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Waktu Layanan Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#rataTabel_wrapper .col-md-6:eq(0)");
            $("#terlamaTabel")
                .DataTable({
                    destroy: true, // Hapus tabel yang sudah ada sebelumnya
                    data: [
                        {
                            kategori:
                                "Tunggu Terlama di Pendaftaran, Mulai di panggil sampai selesai di daftar",
                            waktu_terlama: data.max_tunggu_daftar.toFixed(2),
                            background:
                                data.max_tunggu_daftar > 60 ? "red" : "green",
                        },
                        {
                            kategori:
                                "Tunggu Terlama RM Siap, Mulai di panggil sampai RM Siap",
                            waktu_terlama: data.max_tunggu_rm.toFixed(2),
                            background:
                                data.max_tunggu_rm > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Terlama di Tensi",
                            waktu_terlama: data.max_tunggu_tensi.toFixed(2),
                            background:
                                data.max_tunggu_tensi > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Terlama di Poli",
                            waktu_terlama: data.max_tunggu_poli.toFixed(2),
                            background:
                                data.max_tunggu_poli > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Terlama di Lab",
                            waktu_terlama: data.max_tunggu_lab.toFixed(2),
                            background:
                                data.max_tunggu_lab > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Terlama Hasil Lab",
                            waktu_terlama: data.max_tunggu_hasil_lab.toFixed(2),
                            background:
                                data.max_tunggu_hasil_lab > 60
                                    ? "red"
                                    : "green",
                        },
                        {
                            kategori: "Tunggu Terlama Hasil RO",
                            waktu_terlama: data.max_tunggu_hasil_ro.toFixed(2),
                            background:
                                data.max_tunggu_hasil_ro > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Terlama di IGD",
                            waktu_terlama: data.max_tunggu_igd.toFixed(2),
                            background:
                                data.max_tunggu_igd > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Terlama di Farmasi",
                            waktu_terlama: data.max_tunggu_farmasi.toFixed(2),
                            background:
                                data.max_tunggu_farmasi > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Tunggu Terlama di Kasir",
                            waktu_terlama: data.max_tunggu_kasir.toFixed(2),
                            background:
                                data.max_tunggu_kasir > 60 ? "red" : "green",
                        },
                        {
                            kategori: "Durasi Terlama di Poli dari Pendaftaran",
                            waktu_terlama: data.max_durasi_poli.toFixed(2),
                            background:
                                data.max_durasi_poli > 60 ? "red" : "green",
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
                    order: [[1, "dsc"]], // Urutkan berdasarkan kolom kedua (rata_waktu) secara ascending
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
                            title:
                                "Waktu Layanan Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Waktu Layanan Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#terlamaTabel_wrapper .col-md-6:eq(0)");
            $("#penunjangTabel")
                .DataTable({
                    destroy: true, // Hapus tabel yang sudah ada sebelumnya
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
                    order: [[1, "asc"]], // Urutkan berdasarkan kolom kedua (rata_waktu) secara ascending
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
                            title:
                                "Waktu Layanan Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Waktu Layanan Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#terlamaTabel_wrapper .col-md-6:eq(0)");

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
        error: function (err) {
            console.error("Error fetching data:", err);
        },
    });
}

function updtWaktuLayanan(tglAwal, tglAkhir, tanggal) {
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    cariDataLayanan(tglAwal, tglAkhir, tanggal);
    setTimeout(function () {
        Swal.close();
    }, 2000);
}
function cariDataLayanan(tglAwal, tglAkhir, tanggal) {
    //ternary cek apakah tglAwal dan tglAkhir null
    var tglAwal = tglAwal ? tglAwal : document.getElementById("tanggal").value;
    var tglAkhir = tglAkhir
        ? tglAkhir
        : document.getElementById("tanggal").value;
    var tanggal = tanggal ? tanggal : document.getElementById("tanggal").value;

    ratawaktulayanan(tglAwal, tglAkhir, tanggal);
    waktuLayanan(tglAwal, tglAkhir, tanggal);
}

function setTodayDate() {}

window.addEventListener("load", function () {
    getChartData();
    // setTodayDate();
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);

    tglAkhir = new Date();
    tglAwal = new Date();
    tglAwal.setDate(tglAwal.getDate() - 30);
    tglAkhir.setDate(tglAkhir.getDate() - 1);

    // Menetapkan nilai ke input tanggal
    tglAwal.value = tglAwal.toISOString().split("T")[0];
    tglAkhir.value = tglAkhir.toISOString().split("T")[0];
    cariDataLayanan();
    //Date range picker
    $("#reservation, #ratawaktulayanan").daterangepicker({
        startDate: tglAwal,
        endDate: tglAkhir,
        autoApply: true, // Apply selection automatically when selecting a date range
        locale: {
            format: "YYYY-MM-DD",
            separator: " to ",
            applyLabel: "Apply",
            cancelLabel: "Cancel",
            customRangeLabel: "Custom Range",
        },
    });
    $("#reservation").on("apply.daterangepicker", function (ev, picker) {
        tglAwal = picker.startDate.format("YYYY-MM-DD");
        tglAkhir = picker.endDate.format("YYYY-MM-DD");

        // Lakukan sesuatu dengan startDate dan endDate
        console.log("Start Date: " + tglAwal);
        console.log("End Date: " + tglAkhir);
        Swal.fire({
            icon: "info",
            title: "Sedang mencarikan data...!!!",
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
        waktuLayanan(tglAwal, tglAkhir);
    });

    $("#ratawaktulayanan").on("apply.daterangepicker", function (ev, picker) {
        tglAwal = picker.startDate.format("YYYY-MM-DD");
        tglAkhir = picker.endDate.format("YYYY-MM-DD");
        // Lakukan sesuatu dengan startDate dan endDate
        console.log("ratawaktulayanan Start Date: " + tglAwal);
        console.log("ratawaktulayanan End Date: " + tglAkhir);
        Swal.fire({
            icon: "info",
            title: "Sedang mencarikan data...!!!",
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
        updtWaktuLayanan(tglAwal, tglAkhir, tglAkhir);
        // ratawaktulayanan(tglAwal, tglAkhir);
    });
});
