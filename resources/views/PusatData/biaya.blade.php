<div class="card card-primary">
    <div class="card-header">
        <h4 class="card-title font-weight-bold">Data Biaya Kunjungan pasien baru, dan distribusi
            pasien berkunjung
            ulang</h4>
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
                        <input type="text" class="form-control" id="reservation">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-form"
                            onclick="cariDataKunjungan(
                                                    $('#reservation').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                                                    $('#reservation').data('daterangepicker').endDate.format('YYYY-MM-DD')
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

        <div class="table-responsive pt-2 px-2" id="dataKunjungan">
            <table id="kunjunganTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
                aria-describedby="kunjunganTable_info">
                <thead class="bg-info">
                    <tr>
                        <th rowspan="2">No RM</th>
                        <th class="align-item-center " rowspan="2">Total
                            Kunjungan</th>
                        <th class="align-item-center " rowspan="2">Tanggal
                            Pertama</th>
                        <th class="align-item-center " rowspan="2">Tanggal
                            Kedua</th>
                        <th class="align-item-center " rowspan="2">Kelurahan</th>
                        <th class="align-item-center " rowspan="2">Kabupaten</th>
                        <th class="align-item-center " rowspan="2">Tagihan
                            Baru</th>
                        <th class="text-center" colspan="3" rowspan="1">Jaminan</th>
                        <th class="align-item-center " rowspan="2">Datang Lagi
                        </th>
                    </tr>
                    <tr>
                        <th>Saat Baru </th>
                        <th>Kontrol - Umum</th>
                        <th>Kontrol - BPJS</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
