@extends('Template.lte')

@section('content')
    @include('PusatData.input')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script>
        var tglAwal;
        var tglAkhir;
        var myChart;


        function cariDataKunjungan(tglAwal, tglAkhir) {
            Swal.fire({
                title: 'Memuat Data, Mohon Tunggu...',
                showConfirmButton: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Cek jika DataTable sudah ada, hancurkan dengan clear untuk menghindari duplikasi data
            if ($.fn.DataTable.isDataTable("#kunjunganTable")) {
                $("#kunjunganTable").DataTable().clear().destroy();
            }

            $.ajax({
                url: "/api/data/analis/biaya_pasien",
                method: "POST",
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir,
                },
                dataType: "json",
                success: function(response) {
                    $("#dataKunjungan").html(response.html); // Isi tabel dengan hasil response

                    // Inisialisasi ulang DataTables setelah data dimuat
                    var table = $("#kunjunganTable").DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: true,
                        searching: true,
                        paging: true,
                        ordering: false,
                        order: [
                            [1, "asc"]
                        ],
                        info: true,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Tidak ada data tersedia",
                            zeroRecords: "Tidak ada data yang cocok",
                            paginate: {
                                first: "Awal",
                                last: "Akhir",
                                next: "→",
                                previous: "←"
                            }
                        },
                        buttons: [{
                            extend: "copyHtml5",
                            text: "Salin",
                        }, {
                            extend: "excel", // Tombol ekspor ke Excel
                            text: "Download",
                            title: "Data Pasien Baru & Kunjungan Ulang " + tglAwal + " s.d. " +
                                tglAkhir,
                            filename: "Data_Analisis_Biaya_Pasien_" + tglAwal + "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#kunjunganTable_wrapper .col-md-6:eq(0)");

                    Swal.close();
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Memuat Data",
                        text: "Terjadi kesalahan saat mengambil data: " + error,
                    });
                }
            });
        }

        function cariDataFaksesPerujuk(tglAwal, tglAkhir) {
            Swal.fire({
                title: 'Memuat Data, Mohon Tunggu...',
                showConfirmButton: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Cek jika DataTable sudah ada, hancurkan dengan clear untuk menghindari duplikasi data
            if ($.fn.DataTable.isDataTable("#faskesPerujukTable")) {
                $("#faskesPerujukTable").DataTable().clear().destroy();
            }

            $.ajax({
                url: "/api/data/analis/faskes_perujuk",
                method: "POST",
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir,
                },
                dataType: "json",
                success: function(response) {
                    $("#dataFaskesPerujuk").html(response.html); // Isi tabel dengan hasil response

                    // Inisialisasi ulang DataTables setelah data dimuat
                    var table = $("#faskesPerujukTable").DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: false,
                        searching: true,
                        paging: true,
                        ordering: true,
                        info: true,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Tidak ada data tersedia",
                            zeroRecords: "Tidak ada data yang cocok",
                            paginate: {
                                first: "Awal",
                                last: "Akhir",
                                next: "→",
                                previous: "←"
                            }
                        },
                        buttons: [{
                            extend: "excel", // Tombol ekspor ke Excel
                            text: "Download",
                            title: "Data Faskes Perujuk " + tglAwal +
                                " s.d. " +
                                tglAkhir,
                            filename: "Data_Faskes_Perujuk_" + tglAwal + "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#faskesPerujukTable_wrapper .col-md-6:eq(0)");

                    Swal.close();
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Memuat Data",
                        text: "Terjadi kesalahan saat mengambil data: " + error,
                    });
                }
            });
        }

        function getChartData() {
            Swal.fire({
                title: 'Memuat Data, Mohon Tunggu...',
                showConfirmButton: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Mengambil tahun dari input
            var selectedYear = $("#year-selector").val();

            // Mengambil data dari endpoint 'chart' menggunakan AJAX dengan tahun sebagai parameter
            $.ajax({
                url: "/api/report_igd",
                method: "GET",
                data: {
                    year: selectedYear, // Mengirim tahun sebagai parameter
                },
                success: function(response) {
                    var data = response;
                    console.log("🚀 ~ getChartData ~ data:", data);

                    var selectedYear = $("#year-selector").val();
                    console.log("🚀 ~ getChartData ~ selectedYear:", selectedYear);
                    drawChart(data, selectedYear);
                    tabelIgd(data, selectedYear);
                },
            });
        }


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

        function drawChart(data, tahun) {
            console.log("🚀 ~ drawChart ~ data:", data);

            // Data untuk dataset 'umum', 'bpjs', dan 'totalKunjungan'
            var umumData = Array(12).fill(0); // Inisialisasi array dengan 12 elemen nol
            var bpjsData = Array(12).fill(0);
            var totalKunjunganData = Array(12).fill(0); // Array untuk total kunjungan per bulan
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

            var canvas = document.getElementById("chartIgd");
            var cardBody = document.getElementById("divChartIGD");

            // Mengatur lebar dan tinggi canvas sesuai dengan lebar dan tinggi card-body
            canvas.width = cardBody.offsetWidth;
            canvas.height = cardBody.offsetHeight;

            // Jika data bukan array, konversikan menjadi array
            if (!Array.isArray(data)) {
                data = Object.values(data); // Mengonversi objek menjadi array
            }

            // Mengisi data bulan dengan nilai dari respons JSON
            data.forEach(function(item) {
                var bulanIndex = item.bulan -
                    1; // Mengonversi nilai bulan ke indeks array (dikurangi 1 karena indeks dimulai dari 0)
                var kelompok = item.kelompok.toLowerCase();
                var totalKunjungan = item.totalKunjungan;

                // Update jumlah per kelompok untuk bulan yang sesuai
                if (kelompok === "umum") {
                    umumData[bulanIndex] = item.jumlah;
                } else if (kelompok === "bpjs") {
                    bpjsData[bulanIndex] = item.jumlah;
                }

                // Update total kunjungan untuk bulan yang sesuai
                totalKunjunganData[bulanIndex] = totalKunjungan;
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
                    datasets: [{
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
                        {
                            label: "Total Kunjungan",
                            data: totalKunjunganData, // Setiap bulan total kunjungan
                            backgroundColor: "rgba(54, 162, 235, 0.2)", // Warna untuk total kunjungan
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                            // type: "line", // Tipe grafik untuk total kunjungan (garis)
                            // fill: false, // Tidak ada area yang diisi di bawah garis
                        },
                    ],
                },
                options: options,
            });

            // Menyesuaikan tinggi canvas agar sesuai dengan ukuran card-body
            window.addEventListener("resize", function() {
                console.log("🚀 ~ window.addEventListener ~ resize:")
                canvas.width = cardBody.offsetWidth;

                canvas.height = cardBody.offsetHeight;
                myChart.resize();
            });

            Swal.close()
        }

        function tabelIgd(data, tahun) {
            // Jika data bukan array, konversikan menjadi array
            if (!Array.isArray(data)) {
                data = Object.values(data); // Mengonversi objek menjadi array
            }
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
                    destroy: true,
                    data: data,
                    columns: [{
                            data: "bulan"
                        },
                        {
                            data: "bulan",
                            render: function(data) {
                                // Mengembalikan nama bulan berdasarkan indeks bulan (0 sampai 11)
                                return namaBulan[data - 1]; // Mengurangi 1 karena indeks dimulai dari 0
                            },
                        },
                        {
                            data: "kelompok"
                        },
                        {
                            data: "jumlah"
                        },
                    ],
                    order: [
                        [0, "asc"]
                    ], // Urutan berdasarkan kolom pertama (bulan) secara ascending
                    paging: true,
                    searching: false,
                    info: true,
                    pageLength: 5,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"],
                    ],
                    buttons: [{
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

        $("#year-selector").on("change", function() {
            getChartData();
        });



        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

            // Menetapkan nilai ke input tanggal
            $("#reservation, #tglFaskesPerujuk").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#reservation, #tglFaskesPerujuk").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                autoApply: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " s.d. ",
                    applyLabel: "Pilih",
                    cancelLabel: "Batal",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#reservation, #tglFaskesPerujuk").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");
                }
            );
        });
    </script>
@endsection
