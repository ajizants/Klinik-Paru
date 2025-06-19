<div class="card card-orange">
    <div class="card-header text-light">
        <h6 class="card-title font-weight-bold">Rekap Jumlah Kunjungan Pendaftaran Pasien</h6>
    </div>
    <div class="card-body shadow">
        <div class="row">
            <!-- Input Group -->
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <div class="d-flex align-items-center">
                        <select id="tahunPendaftaran" class="form-control form-control-sm mr-2">
                            @for ($year = date('Y'); $year >= 2021; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <button class="btn btn-sm btn-success"
                            onclick="reportPendaftaran(document.getElementById('tahunPendaftaran').value)">Cari</button>
                    </div>

                </div>
            </div>

            <!-- Accordion -->
            <div class="col-md-8">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <a class="btn btn-link text-left w-100 p-1" type="button" data-toggle="collapse"
                            id="headingOne" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
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
        <div class="table-responsive pt-2 px-2" id="divTabelJumlahPendaftaran">
            <table class="table table-bordered table-hover dataTable dtr-inline" id="jumlahPendaftaranTable"
                cellspacing="0">
                <thead class="bg bg-teal table-bordered border-warning">
                    <tr>
                        <th rowspan="2" class="align-middle">Keterangan</th>
                        <th rowspan="2" class="text-center align-middle">Total</th>
                        <th colspan="3" class="text-center">Jaminan</th>
                    </tr>
                    <tr>
                        <th class="text-center">BPJS</th>
                        <th class="text-center">BPJS PERIODE 2</th>
                        <th class="text-center">UMUM</th>
                    </tr>
                </thead>
                <tbody class="table-bordered">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function reportPendaftaran(tahun) {
        tampilkanLoading("Memuat data jumlah pendaftaran...");

        $.ajax({
            url: "/api/pendaftaran/report/" + tahun,
            type: "GET",
            success: function(response) {
                let combinedHtml = "";

                // Loop hanya pada response.html
                for (const bulan in response.html) {
                    if (response.html.hasOwnProperty(bulan)) {
                        combinedHtml += `<h5 class="mt-4 mb-2">Bulan ${bulan}</h5>`;
                        combinedHtml += response.html[bulan];
                    }
                }

                $("#divTabelJumlahPendaftaran").html(combinedHtml);

                for (const bulan in response.html) {
                    if (response.html.hasOwnProperty(bulan)) {
                        const tableId =
                            `#rekapTotal${bulan.replace(".", "")}`; // remove dot from ID if exists
                        $(tableId)
                            .DataTable({
                                autoWidth: false,
                                ordering: false,
                                paging: false,
                                searching: false,
                                info: false,
                                lengthChange: false,
                                buttons: [{
                                        extend: "excelHtml5",
                                        text: "Excel",
                                        title: "Laporan Jumlah Pendaftaran Bulan " + bulan + " (" +
                                            tglAwal + " s.d. " + tglAkhir + ")",
                                        filename: "Laporan_Jumlah_Pendaftaran_Bulan_" + bulan +
                                            "_" + tglAwal + "_sd_" + tglAkhir,
                                    },
                                    {
                                        extend: "colvis",
                                        text: "Tampilkan Kolom",
                                    },
                                ],
                            })
                            .buttons()
                            .container()
                            .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
                    }
                }

                Swal.close();
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
                        error,
                });
            },
        });
    }
    // function reportPendaftaran(tglAwal, tglAkhir) {
    //     tampilkanLoading("Memuat data jumlah pendaftaran...");

    //     $.ajax({
    //         url: "/api/pendaftaran/report",
    //         type: "post",
    //         data: {
    //             tanggal_awal: tglAwal,
    //             tanggal_akhir: tglAkhir,
    //             no_rm: "",
    //         },
    //         success: function(response) {
    //             var html = response["html"];
    //             // Inisialisasi DataTable

    //             $("#divTabelJumlahPendaftaran").html(html);
    //             $("#jumlahPendaftaranTable")
    //                 .DataTable({
    //                     autoWidth: false,
    //                     ordering: false,
    //                     paging: false,
    //                     searching: false,
    //                     info: false,
    //                     lengthChange: false,
    //                     buttons: [{
    //                             extend: "excelHtml5",
    //                             text: "Excel",
    //                             title: "Laporan Jumlah Pendaftaran Tanggal: " +
    //                                 tglAwal +
    //                                 " s.d. " +
    //                                 tglAkhir,
    //                             filename: "Laporan Jumlah Pendaftaran Tanggal " +
    //                                 tglAwal +
    //                                 " s.d. " +
    //                                 tglAkhir,
    //                         },
    //                         {
    //                             extend: "colvis",
    //                             text: "Tampilkan Kolom",
    //                         },
    //                     ],
    //                 })
    //                 .buttons()
    //                 .container()
    //                 .appendTo("#rekapTotal_wrapper .col-md-6:eq(0)");

    //             Swal.close();
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Error:", error);
    //             Swal.fire({
    //                 icon: "error",
    //                 title: "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
    //                     error,
    //             });
    //         },
    //     });
    // }
</script>
