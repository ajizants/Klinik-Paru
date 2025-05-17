    <div class="card card-lime">
        <div class="card-header bg-lime">
            <h4 class="card-title font-weight-bold">Data Jumlah Pelayanan Dokter</h4>
        </div>
        <div class="card-body shadow">
            <div class="row">
                <!-- Input Group -->
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                {{-- <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span> --}}
                                <select name="tahun" id="tahun" class="form-control bg bg-warning m-2">
                                    <option value="">-- Pilih Tahun --</option>
                                    @for ($year = date('Y'); $year >= 2021; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-form"
                                onclick="cariDataDokterPeriksa(document.getElementById('tahun').value)">Cari</button>
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

            <div class="table-responsive pt-2 px-2" id="divJumlahDokter">
            </div>
            <div class="card mt-4">
                <div class="card-header">Grafik Kunjungan Pemeriksaan Dokter</div>
                <div class="card-body">
                    <canvas id="dokterLineChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cariDataDokterPeriksa(tahun) {
            tampilkanLoading("Sedangan Mencari Data Pemeriksaan Dokter...");
            // cariDataDokterPeriksaItem(tglAwal, tglAkhir);

            $.ajax({
                url: "/api/laporan/dokter_periksa/" + tahun + "/12",
                method: "GET",
                success: function(response) {
                    $('#divJumlahDokter').html(response.html);
                    var table = $('#jumlahDokterTable').DataTable({
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
                            title: "Data Jumlah Pemeriksaa Dokter " + tglAwal + " s.d. " +
                                tglAkhir,
                            filename: "Data Jumlah Pemeriksaa Dokter" + tglAwal + "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#jumlahDokterTable_wrapper .col-md-6:eq(0)");

                    const ctx = document.getElementById('dokterLineChart').getContext('2d');

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
                                    text: 'Grafik Pemeriksaan Dokter per Bulan'
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

        // function cariDataDokterPeriksaItem(tglAwal, tglAkhir) {

        //     $.ajax({
        //         url: "/api/lab/laporan/dokter_periksa/item",
        //         method: "POST",
        //         data: {
        //             tglAwal: tglAwal,
        //             tglAkhir: tglAkhir
        //         },
        //         success: function(response) {
        //             $('#divJumlahLabItem').html(response.html);
        //             var table = $('#jumlahLabItemTable').DataTable({
        //                 responsive: true,
        //                 lengthChange: false,
        //                 autoWidth: true,
        //                 searching: true,
        //                 paging: true,
        //                 // ordering: false,
        //                 order: [
        //                     [0, "asc"]
        //                 ],
        //                 info: true,
        //                 language: {
        //                     search: "Cari:",
        //                     lengthMenu: "Tampilkan _MENU_ data",
        //                     info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        //                     infoEmpty: "Tidak ada data tersedia",
        //                     zeroRecords: "Tidak ada data yang cocok",
        //                     paginate: {
        //                         first: "Awal",
        //                         last: "Akhir",
        //                         next: "→",
        //                         previous: "←"
        //                     }
        //                 },
        //                 buttons: [{
        //                     extend: "copyHtml5",
        //                     text: "Salin",
        //                 }, {
        //                     extend: "excel", // Tombol ekspor ke Excel
        //                     text: "Download",
        //                     title: "Data Jumlah Kunjung Laboratorium per Item" + tglAwal +
        //                         " s.d. " +
        //                         tglAkhir,
        //                     filename: "Data Jumlah Kunjung Laboratorium per Item" + tglAwal +
        //                         "_" +
        //                         tglAkhir,
        //                     exportOptions: {
        //                         columns: ":visible",
        //                     },
        //                 }]
        //             });

        //             // Menambahkan tombol ekspor ke dalam wrapper DataTables
        //             table.buttons().container().appendTo("#jumlahLabItemTable_wrapper .col-md-6:eq(0)");

        //             // Buat chart menggunakan data.chart
        //             const ctx = document.getElementById('chartLabItem').getContext('2d');

        //             new Chart(ctx, {
        //                 type: 'bar', // bisa juga 'line' atau 'bar'
        //                 data: {
        //                     labels: response.chart.labels,
        //                     datasets: response.chart.datasets.map((ds, index) => ({
        //                         ...ds,
        //                         backgroundColor: warna(index),
        //                         borderColor: warna(index),
        //                         borderWidth: 1
        //                     }))
        //                 },
        //                 options: {
        //                     responsive: true,
        //                     plugins: {
        //                         legend: {
        //                             position: 'top',
        //                         },
        //                         tooltip: {
        //                             mode: 'index',
        //                             intersect: false,
        //                         }
        //                     },
        //                     interaction: {
        //                         mode: 'nearest',
        //                         axis: 'x',
        //                         intersect: false
        //                     },
        //                     scales: {
        //                         y: {
        //                             beginAtZero: true
        //                         }
        //                     }
        //                 }
        //             });

        //             Swal.close();
        //         },
        //         error: function(xhr, status, error) {
        //             console.error("Terjadi kesalahan saat mencari data:", error);
        //             Swal.fire({
        //                 icon: "error",
        //                 title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
        //             });
        //         }
        //     })
        // }

        // function warna(index) {
        //     const warnaDasar = [
        //         '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
        //         '#FF9F40', '#00BFFF', '#DC143C', '#008000', '#800080'
        //     ];
        //     return warnaDasar[index % warnaDasar.length];
        // }
    </script>
