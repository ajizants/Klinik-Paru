    <div class="card card-warning">
        <div class="card-header">
            <h4 class="card-title font-weight-bold">Data Jumlah Kunjungan Radiologi</h4>
        </div>
        <div class="card-body shadow">
            <div class="row">
                <!-- Input Group -->
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="tglRo">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-form"
                                onclick="cariDataKunjunganRo(
                                                    $('#tglRo').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                                                    $('#tglRo').data('daterangepicker').endDate.format('YYYY-MM-DD')
                                                )">Cari</button>
                        </div>
                    </div>
                </div>

                <!-- Accordion -->
                <div class="col-md-8">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <a class="btn btn-link text-left w-100" type="button" data-toggle="collapse"
                                id="headingOne" data-target="#collapseOne" aria-expanded="true"
                                aria-controls="collapseOne">
                                <strong>Klik Untuk Melihat Cara Pencarian Data</strong>
                            </a>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <h5>Pencarian Data</h5>
                                    <ul>
                                        <li>Pilih tab data yang akan dicari.</li>
                                        <li>Pilih rentang tanggal.</li>
                                        <li>Untuk satu tanggal, klik dua kali pada tanggal tersebut.
                                        </li>
                                        <li>Klik tombol "Pilih" untuk mencari data.</li>
                                        <li>Klik tombol "Cari" untuk memperbarui data.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive pt-2 px-2" id="divJumlahRo">
            </div>
            <div class="card mt-4">
                <div class="card-header">Grafik Kunjungan Radiologi</div>
                <div class="card-body">
                    <canvas id="rekapRoChart" height="300"></canvas>
                </div>
            </div>
            <div class="table-responsive pt-2 px-2" id="divJumlahRoItem">
            </div>
            <div class="card mt-4">
                <div class="card-header">Grafik Pemeriksaan Radiologi</div>
                <div class="card-body">
                    <canvas id="chartRoItem" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cariDataKunjunganRo(tglAwal, tglAkhir) {
            tampilkanLoading("Sedangan Mencari Data Kunjungan Radiologi...");
            cariDataKunjunganRoItem(tglAwal, tglAkhir);

            $.ajax({
                url: "/api/ro/laporan/kunjungan",
                method: "POST",
                data: {
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                },
                success: function(response) {
                    $('#divJumlahRo').html(response.html);
                    var table = $('#jumlahRoTable').DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: true,
                        searching: true,
                        paging: true,
                        // ordering: false,
                        order: [
                            [0, "asc"]
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
                            title: "Data Jumlah Kunjung Radiologi " + tglAwal + " s.d. " +
                                tglAkhir,
                            filename: "Data Jumlah Kunjung Radiologi" + tglAwal + "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#jumlahRoTable_wrapper .col-md-6:eq(0)");

                    const ctx = document.getElementById('rekapRoChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.chart.labels,
                            datasets: [{
                                    label: 'BPJS',
                                    data: response.chart.datasets[0].data,
                                    borderColor: '#36A2EB',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    tension: 0.3,
                                    fill: true
                                },
                                {
                                    label: 'UMUM',
                                    data: response.chart.datasets[1].data,
                                    borderColor: '#FF6384',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    tension: 0.3,
                                    fill: true
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Grafik Kunjungan Laboratorium per Bulan'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    Swal.close();
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan saat mencari data:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
                    });
                }
            })
        }

        function cariDataKunjunganRoItem(tglAwal, tglAkhir) {
            $.ajax({
                url: "/api/ro/laporan/kunjungan/item",
                method: "POST",
                data: {
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                },
                success: function(response) {
                    $('#divJumlahRoItem').html(response.html);
                    var table = $('#jumlahRoItemTable').DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: true,
                        searching: true,
                        paging: true,
                        // ordering: false,
                        order: [
                            [0, "asc"]
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
                            title: "Data Jumlah Kunjung Radiologi per Item" + tglAwal +
                                " s.d. " +
                                tglAkhir,
                            filename: "Data Jumlah Kunjung Radiologi per Item" + tglAwal +
                                "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#jumlahRoItemTable_wrapper .col-md-6:eq(0)");
                    // Buat chart menggunakan data.chart
                    const ctx = document.getElementById('chartRoItem').getContext('2d');

                    new Chart(ctx, {
                        type: 'bar', // bisa juga 'line' atau 'bar'
                        data: {
                            labels: response.chart.labels,
                            datasets: response.chart.datasets.map((ds, index) => ({
                                ...ds,
                                backgroundColor: warna(index),
                                borderColor: warna(index),
                                borderWidth: 1
                            }))
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    Swal.close();
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan saat mencari data:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
                    });
                }
            })
        }
    </script>
