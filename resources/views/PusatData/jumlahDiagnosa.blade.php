    <div class="card card-lime">
        <div class="card-header bg-lime">
            <h4 class="card-title font-weight-bold">Data Jumlah Diagnosa Medis</h4>
        </div>
        <div class="card-body shadow">
            <div class="row">
                <!-- Input Group -->
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <select id="tahunDx" class="form-control form-control-sm mr-2">
                                @for ($year = date('Y'); $year >= 2021; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                            <button class="btn btn-sm btn-success"
                                onclick="cariJumlahDiagnosa(document.getElementById('tahunDx').value)">Cari</button>
                        </div>

                    </div>
                </div>

                <!-- Accordion -->
                <div class="col-md-8">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <a class="btn btn-link text-left w-100 p-1" type="button" data-toggle="collapse"
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
                                        <li>Pilih rentang tanggal/tahun</li>
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
            <div class="card mt-4">
                <div class="card-header">Data Jumlah Diagnosa Per Tahun</div>
                <div class="card-body">
                    <div class="table-responsive pt-2 px-2" id="divJumlahDxPerTahun">

                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">Data Jumlah Diagnosa Per Bulan</div>
                <div class="card-body">
                    <div class="table-responsive pt-2 px-2" id="divJumlahDx">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function cariJumlahDiagnosa(tahun) {
            tampilkanLoading("Sedangan Mencari Data Pemeriksaan Dokter...");
            // cariDataDokterPeriksaItem(tglAwal, tglAkhir);

            $.ajax({
                url: "/api/data/analis/diagnosa/" + tahun,
                method: "GET",
                success: function(response) {
                    $('#divJumlahDx').html(response.tablePerbulan);
                    var table = $('#jumlahDxTable').DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: true,
                        searching: true,
                        paging: true,
                        ordering: false,
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
                            title: "Data Jumlah Diagnosa Per Bulan " + tahun,
                            filename: "Data Jumlah Diagnosa Per Bulan " + tahun,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#jumlahDxTable_wrapper .col-md-6:eq(0)");


                    $('#divJumlahDxPerTahun').html(response.tablePertahun);
                    var table = $('#jumlahDxPerTahunTable').DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: true,
                        searching: true,
                        paging: true,
                        ordering: false,
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
                            title: "Data Jumlah Diagnosa Tahun " + tahun,
                            filename: "Data Jumlah Diagnosa Tahun " + tahun,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#jumlahDxPerTahunTable_wrapper .col-md-6:eq(0)");



                    Swal.close();
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan saat mencari data:", error);
                    const message = xhr.responseJSON.message || "Terjadi kesalahan saat mencari data...!!!";
                    tampilkanEror(message);
                }
            })
        }
    </script>
