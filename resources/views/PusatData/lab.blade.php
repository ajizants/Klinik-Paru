    <div class="card card-lime">
        <div class="card-header bg-lime">
            <h4 class="card-title font-weight-bold">Data Jumlah Kunjungn Laboratorium</h4>
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
                            <input type="text" class="form-control" id="tglLab">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-form"
                                onclick="cariDataKunjunganLab(
                                                    $('#tglLab').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                                                    $('#tglLab').data('daterangepicker').endDate.format('YYYY-MM-DD')
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

            <div class="table-responsive pt-2 px-2" id="divJumlahLab">
            </div>
            <div class="table-responsive pt-2 px-2" id="divJumlahLabItem">
            </div>
        </div>
    </div>

    <script>
        function cariDataKunjunganLab(tglAwal, tglAkhir) {
            tampilkanLoading("Sedangan Mencari Data Kunjungan Laboratorium...");
            cariDataKunjunganLabItem(tglAwal, tglAkhir);

            $.ajax({
                url: "/api/lab/laporan/kunjungan",
                method: "POST",
                data: {
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                },
                success: function(response) {
                    $('#divJumlahLab').html(response.html);
                    var table = $('#jumlahLabTable').DataTable({
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
                            title: "Data Jumlah Kunjung Laboratorium " + tglAwal + " s.d. " +
                                tglAkhir,
                            filename: "Data Jumlah Kunjung Laboratorium" + tglAwal + "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#jumlahLabTable_wrapper .col-md-6:eq(0)");

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

        function cariDataKunjunganLabItem(tglAwal, tglAkhir) {

            $.ajax({
                url: "/api/lab/laporan/kunjungan/item",
                method: "POST",
                data: {
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                },
                success: function(response) {
                    $('#divJumlahLabItem').html(response.html);
                    var table = $('#jumlahLabItemTable').DataTable({
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
                            title: "Data Jumlah Kunjung Laboratorium per Item" + tglAwal +
                                " s.d. " +
                                tglAkhir,
                            filename: "Data Jumlah Kunjung Laboratorium per Item" + tglAwal +
                                "_" +
                                tglAkhir,
                            exportOptions: {
                                columns: ":visible",
                            },
                        }]
                    });

                    // Menambahkan tombol ekspor ke dalam wrapper DataTables
                    table.buttons().container().appendTo("#jumlahLabItemTable_wrapper .col-md-6:eq(0)");

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
