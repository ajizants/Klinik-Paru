@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
            <h6 class="m-0 font-weight-bold text-primary">Rekap Jumlah Kunjungan</h6>
        </div>
        <div class="card-body mb-2">
            <div class="row">
                <label class="col-form-label">Rentang Tanggal :</label>
                <div class="form-group col-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="reservation">
                    </div>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-success" onclick="segarkan();">
                        Segarkan
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top" title="Update Data"
                            id="cariantrian"></span>
                    </button>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="report" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>
            <div class="container-fluid d-flex justify-content-center p-1 mt-1">
                <div>
                    <canvas id="lineChart" width="1300" height="600"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script>
        var tglAwal;
        var tglAkhir;

        function segarkan() {
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            listData(tglAwal, tglAkhir);
        }

        function listData(tglAwal, tglAkhir) {
            var tglA = formatDate(new Date(tglAwal));
            var tglB = formatDate(new Date(tglAkhir));

            if ($.fn.DataTable.isDataTable("#report")) {
                var tabletindakan = $("#report").DataTable();
                tabletindakan.destroy();
            }

            $.ajax({
                url: "/api/kominfo/report/dokter_rme",
                type: "post",
                data: {
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir,
                },
                success: function(response) {
                    // Menyusun data untuk DataTable
                    var dataTableData = [];
                    var dokterList = [];
                    var groupedData = {};

                    // Mengelompokkan data berdasarkan tanggal dan dokter
                    response.forEach(function(item) {
                        if (!dokterList.includes(item.dokter_nama)) {
                            dokterList.push(item.dokter_nama);
                        }

                        if (!groupedData[item.tanggal]) {
                            groupedData[item.tanggal] = {};
                        }

                        groupedData[item.tanggal][item.dokter_nama] = item.jumlah_farmasi;
                    });

                    // Menyusun data untuk tabel
                    var headers = ['Tanggal'].concat(dokterList);
                    var theadHTML = '<tr>';
                    headers.forEach(function(header) {
                        theadHTML += '<th>' + header + '</th>';
                    });
                    theadHTML += '</tr>';
                    $('#report thead').html(theadHTML); // Update the header

                    // Menyusun baris data
                    for (var tanggal in groupedData) {
                        var row = [tanggal];
                        dokterList.forEach(function(dokter) {
                            row.push(groupedData[tanggal][dokter] || 0); // Jika tidak ada data, set 0
                        });
                        dataTableData.push(row);
                    }

                    // Inisialisasi DataTable dengan data yang sudah disusun
                    var tabletindakan = $("#report").DataTable({
                            data: dataTableData,
                            responsive: true,
                            autoWidth: false,
                            paging: true,
                            lengthMenu: [
                                [5, 10, 25, 50, -1],
                                [5, 10, 25, 50, "All"],
                            ],
                            pageLength: 5,
                            buttons: [{
                                    extend: "excelHtml5",
                                    text: "Excel",
                                    title: "Laporan Pendaftaran Tanggal: " +
                                        tglA +
                                        " s.d. " +
                                        tglB,
                                    filename: "Laporan Pendaftaran Tanggal: " +
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
                        .appendTo("#report_wrapper .col-md-6:eq(0)");

                    // Panggil fungsi untuk membuat chart
                    createLineChart(response);

                    Swal.close();
                }
            });
        }

        function createLineChart(response) {
            // Data dan labels untuk chart
            var chartLabels = [];
            var chartData = [];
            var doctorDataMap = {};

            // Mengelompokkan data berdasarkan tanggal dan dokter
            response.forEach(function(item) {
                var label = item.tanggal; // Combine tanggal and dokter name for X-axis label
                if (!chartLabels.includes(label)) {
                    chartLabels.push(label);
                }

                // Organize data for each doctor
                if (!doctorDataMap[item.dokter_nama]) {
                    doctorDataMap[item.dokter_nama] = [];
                }

                // doctorDataMap[item.dokter_nama].push(item.jumlah_farmasi);
                doctorDataMap[item.dokter_nama].push(item.percentage);
            });

            // Cek jika chart sudah ada dan hapus
            if (window.chartInstance) {
                window.chartInstance.destroy(); // Hapus chart sebelumnya
            }

            // Ambil konteks canvas
            var ctx = document.getElementById('lineChart').getContext('2d');

            // Inisialisasi chart baru
            // window.chartInstance = new Chart(ctx, {
            //     type: 'line', // Jenis chart
            //     data: {
            //         labels: chartLabels, // Label (X-axis)
            //         datasets: Object.keys(doctorDataMap).map(function(doctorName) {
            //             return {
            //                 label: doctorName, // Set the doctor name as the label for each line
            //                 data: doctorDataMap[doctorName], // Data for each doctor
            //                 borderColor: getRandomColor(), // Random color for each line
            //                 backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna background area chart
            //                 fill: false, // Mengisi area di bawah garis
            //                 borderWidth: 4, // Ketebalan garis
            //                 tension: 0.4 // Kelengkungan garis
            //             };
            //         })
            //     },
            //     options: {
            //         responsive: true, // Responsif
            //         plugins: {
            //             legend: {
            //                 display: true // Menampilkan legenda
            //             }
            //         },
            //         scales: {
            //             x: {
            //                 beginAtZero: true // Menampilkan sumbu X mulai dari 0

            //             },
            //             y: {
            //                 beginAtZero: true, // Menampilkan sumbu Y mulai dari 0  
            //                 max: 200
            //             }
            //         }
            //     }
            // });
            window.chartInstance = new Chart(ctx, {
                type: 'line', // Jenis chart
                data: {
                    labels: chartLabels, // Label (X-axis)
                    datasets: Object.keys(doctorDataMap).map(function(doctorName) {
                        return {
                            label: doctorName, // Set the doctor name as the label for each line
                            data: doctorDataMap[doctorName], // Data for each doctor
                            borderColor: getRandomColor(), // Random color for each line
                            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna background area chart
                            fill: false, // Tidak mengisi area di bawah garis
                            borderWidth: 4, // Ketebalan garis
                            tension: 0.4 // Kelengkungan garis
                        };
                    })
                },
                options: {
                    responsive: true, // Responsif
                    plugins: {
                        legend: {
                            display: true // Menampilkan legenda
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true // Sumbu X mulai dari 0
                        },
                        y: {
                            beginAtZero: true, // Sumbu Y mulai dari 0
                            min: 0, // Nilai minimum pada sumbu Y
                            max: 200, // Nilai maksimum pada sumbu Y
                            ticks: {
                                stepSize: 5 // Langkah skala (opsional)
                            }
                        }
                    }
                }
            });
        }

        // Array untuk menyimpan warna yang sudah digunakan
        let usedColors = [];

        function getRandomColor() {
            let color;
            do {
                // Generate a random hue between 0 and 360
                var hue = Math.floor(Math.random() * 360);

                // Set saturation and lightness for more vibrant and contrasting colors
                var saturation = '70%';
                var lightness = '50%';

                // Hasilkan warna dalam format HSL
                color = 'hsl(' + hue + ', ' + saturation + ', ' + lightness + ')';
            } while (usedColors.includes(color)); // Ulangi jika warna sudah digunakan

            // Simpan warna baru ke dalam array usedColors
            usedColors.push(color);

            return color;
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

        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

            // Menetapkan nilai ke input tanggal
            $("#reservation, #tglJumlah").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#reservation, #tglJumlah").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                autoApply: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " to ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#reservation, #tglJumlah").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");

                    // Lakukan sesuatu dengan startDate dan endDate
                    Swal.fire({
                        icon: "info",
                        title: "Sedang mencarikan data...!!!",
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    listData(tglAwal, tglAkhir);
                }
            );
        });
    </script>
@endsection
