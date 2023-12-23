// Fungsi untuk mengambil data dari endpoint 'chart' berdasarkan tahun yang dipilih
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

            // Menggambar grafik dengan data yang diterima
            drawChart(data);
        },
    });
}

function drawChart(data) {
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

    // Menginisialisasi data bulan dengan nilai nol
    var bulanData = {
        Januari: 0,
        Februari: 0,
        Maret: 0,
        April: 0,
        Mei: 0,
        Juni: 0,
        Juli: 0,
        Agustus: 0,
        September: 0,
        Oktober: 0,
        November: 0,
        Desember: 0,
    };

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

    // Memisahkan data ke dalam umumData dan bpjsData
    for (var bulan in bulanData) {
        if (bulanData.hasOwnProperty(bulan)) {
            umumData.push(bulanData[bulan]);
            bpjsData.push(bulanData[bulan]);
        }
    }

    // Pengaturan Grafik
    var options = {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    };

    // Menggambar Grafik menggunakan Chart.js
    var ctx = document.getElementById("myAreaChart").getContext("2d");
    var myChart = new Chart(ctx, {
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
}

$("#year-selector").on("change", function () {
    // Memanggil fungsi getChartData() saat tahun berubah
    getChartData();
});

getChartData();
