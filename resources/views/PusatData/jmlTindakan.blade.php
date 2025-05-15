<div class="card card-info">
    <div class="card-header text-light">
        <h6 class="card-title font-weight-bold"><b>Rekapan Jumlah Tindakan</b></h6>
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
                        <input type="text" class="form-control" id="tglTindakan">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-form"
                            onclick="jumlahTindakan(
                            $('#tglTindakan').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                            $('#tglTindakan').data('daterangepicker').endDate.format('YYYY-MM-DD')
                        )">Cari</button>
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
        <div class="table-responsive  mt-2" id="divJmlhTindakanTable">
            <table class="table table-bordered table-hover dataTable dtr-inline" id="tableRekapJumlahTindakan"
                cellspacing="0">
                <thead class="bg bg-info table-bordered border-warning">
                    <tr>
                        <th>No</th>
                        <th>Kode Tindakan</th>
                        <th>Nama Tindakan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody class="table-bordered border-warning">
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card shadow">
    <!-- Card Header -->
    <div class="card-header bg-warning d-flex justify-content-start align-items-center">
        <h6 class="card-title font-weight-bold mr-4 pt-1 ">Data Kunjungan IGD Tahun: </h6>
        <div class="d-flex align-items-center">
            <select id="year-selector" class="form-control form-control-sm mr-2">
                @for ($year = date('Y'); $year >= 2021; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
            <button class="btn btn-sm btn-success" onclick="getChartData()">Cari</button>
        </div>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <div class="row">
            <!-- Grafik -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Grafik Kunjungan Pasien IGD
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="chartIgd"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tabel Kunjungan IGD</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabelIgd" width="100%" cellspacing="0">
                                <thead class="bg-teal">
                                    <tr>
                                        <th>Kd</th>
                                        <th>Bulan</th>
                                        <th>Layanan</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan dimasukkan secara dinamis -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->
    </div> <!-- card-body -->
</div> <!-- card -->
<script>
    function jumlahTindakan(tglAwal, tglAkhir) {
        tampilkanLoading("Memuatkan data jumlah tindakan...");
        $.ajax({
            url: "/api/getRekapJumlahTindakan",
            type: "POST",
            dataType: "json",
            contentType: "application/json",
            data: JSON.stringify({
                tglAwal: tglAwal,
                tglAkhir: tglAkhir,
            }),
            beforeSend: function() {
                Swal.fire({
                    title: "Mengambil data...",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            },
            success: function(result) {
                console.log("🚀 ~ response data:", result);

                // Pastikan data tidak kosong
                if (!result.html || result.html.trim() === "") {
                    Swal.fire({
                        icon: "warning",
                        title: "Data kosong atau tidak valid!",
                    });
                    return;
                }

                // Masukkan data ke dalam div
                $("#divJmlhTindakanTable").html(result.html);

                // Pastikan elemen tabel ada sebelum inisialisasi DataTables
                if ($("#tableRekapJumlahTindakan").length) {
                    // Hapus DataTables lama jika sudah ada
                    if ($.fn.DataTable.isDataTable("#tableRekapJumlahTindakan")) {
                        $("#tableRekapJumlahTindakan").DataTable().destroy();
                    }

                    // Inisialisasi ulang DataTables
                    var table = $("#tableRekapJumlahTindakan").DataTable({
                        responsive: true,
                        lengthChange: false,
                        autoWidth: true,
                        searching: true,
                        paging: true,
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
                                previous: "←",
                            },
                        },
                        buttons: [{
                                extend: "copyHtml5",
                                text: "Salin",
                            },
                            {
                                extend: "excel",
                                text: "Download",
                                title: `Data Jumlah Tindakan ${tglAwal} s.d. ${tglAkhir}`,
                                filename: `Data_Jumlah_Tindakan_${tglAwal}_${tglAkhir}`, // Nama file ekspor
                                exportOptions: {
                                    columns: ":visible"
                                },
                            },
                        ],
                    });

                    // Tambahkan tombol ekspor ke dalam wrapper DataTables
                    table
                        .buttons()
                        .container()
                        .appendTo(
                            "#tableRekapJumlahTindakan_wrapper .col-md-6:eq(0)"
                        );
                }

                Swal.close();
            },
            error: function(xhr, status, error) {
                console.error("🚨 Error:", error);
                Swal.fire({
                    icon: "error",
                    title: `Terjadi kesalahan saat mengambil data...!!!\n${xhr.status} - ${xhr.statusText}`,
                });
            },
        });
    }
</script>
