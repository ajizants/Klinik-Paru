<div class="card card-lime">
    <div class="card-header bg-lime">
        <h4 class="card-title font-weight-bold">Rekapan Jumlah Kunjungan Per Wilayah</h4>
    </div>
    <div class="card-body shadow">
        <div class="row">
            <!-- Input Group -->
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <div class="d-flex align-items-center">
                        <select id="tahunKunjungan" class="form-control form-control-sm mr-2">
                            <option value="all">Semua Data</option>
                            @for ($year = date('Y'); $year >= 2021; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <button class="btn btn-sm btn-success"
                            onclick="rekapKunjunganKecamatan(document.getElementById('tahunKunjungan').value)">Cari</button>
                    </div>

                </div>
            </div>

            <!-- Accordion -->
            <div class="col-md-8">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <a class="btn btn-link text-left w-100" type="button" data-toggle="collapse" id="headingOne"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
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
        <div class="card">
            <div class="card-header">Rekapan Jumlah Kunjungan Per Wilayah Tahunan</div>
            <div class="card-body">
                <div class="table-responsive pt-2 px-2" id="divWilayahTahunan">
                    <table class="table table-bordered table-hover dataTable dtr-inline"
                        id="tablePendaftaranPerKecamatanTahunan">
                        <thead class="bg bg-info">
                            <tr>
                                <th>Bulan</th>
                                <th>Kode Kab</th>
                                <th>Kabupaten</th>
                                <th>Kode Kec</th>
                                <th>Kecamatan</th>
                                <th>Jumlah Kunjungan</th>
                                <th>UMUM</th>
                                <th>BPJS</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered border-warning">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Rekapan Jumlah Kunjungan Per Wilayah Bulanan</div>
            <div class="card-body">
                <div class="table-responsive" id="divWilayahBulanan">
                    <table class="table table-bordered table-hover dataTable dtr-inline"
                        id="tablePendaftaranPerKecamatanBulanan">
                        <thead class="bg bg-info">
                            <tr>
                                <th>Bulan</th>
                                <th>Kode Kab</th>
                                <th>Kabupaten</th>
                                <th>Kode Kec</th>
                                <th>Kecamatan</th>
                                <th>Jumlah Kunjungan</th>
                                <th>UMUM</th>
                                <th>BPJS</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered border-warning">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    async function rekapKunjunganKecamatan(tahun) {
        tampilkanLoading('Sedangan Mencari Data Kunjungan Per Wilayah...');
        $.ajax({
            url: "/api/pendaftaran/getPendaftaranPerKecamatan/" + tahun,
            method: "GET",
            success: function(response) {
                $('#divWilayahTahunan').html(response.tahunan);
                $('#divWilayahBulanan').html(response.bulanan);
                var table = $('#tablePendaftaranPerKecamatanTahunan').DataTable({
                    responsive: true,
                    lengthChange: false,
                    autoWidth: true,
                    searching: true,
                    paging: true,
                    // ordering: false,
                    order: [
                        [5, "desc"]
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
                        title: "Data Jumlah Kunjung Per Wilayah Tahunan " + tglAwal +
                            " s.d. " +
                            tglAkhir,
                        filename: "Data Jumlah Kunjung Per Wilayah Tahunan" + tglAwal +
                            "_" +
                            tglAkhir,
                        exportOptions: {
                            columns: ":visible",
                        },
                    }]
                });
                var table2 = $('#tablePendaftaranPerKecamatanBulanan').DataTable({
                    responsive: true,
                    lengthChange: false,
                    autoWidth: true,
                    searching: true,
                    paging: true,
                    // ordering: false,
                    order: [
                        [5, "desc"]
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
                        title: "Data Jumlah Kunjung Per Wilayah Bulanan " + tglAwal +
                            " s.d. " +
                            tglAkhir,
                        filename: "Data Jumlah Kunjung Per Wilayah Bulanan" + tglAwal +
                            "_" +
                            tglAkhir,
                        exportOptions: {
                            columns: ":visible",
                        },
                    }]
                });

                // Menambahkan tombol ekspor ke dalam wrapper DataTables
                table.buttons().container().appendTo(
                    "#tablePendaftaranPerKecamatanTahunan_wrapper .col-md-6:eq(0)");
                table2.buttons().container().appendTo(
                    "#tablePendaftaranPerKecamatanBulanan_wrapper .col-md-6:eq(0)");


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
