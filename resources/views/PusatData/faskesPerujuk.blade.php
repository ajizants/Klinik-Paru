<div class="card card-orange">
    <div class="card-header text-light">
        <h6 class="card-title font-weight-bold">Rekap Jumlah Faskes Perujuk</h6>
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
                        <input type="text" class="form-control" id="tglFaskesPerujuk">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-form"
                            onclick="cariDataFaskesPerujuk(
                                    $('#tglFaskesPerujuk').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                                    $('#tglFaskesPerujuk').data('daterangepicker').endDate.format('YYYY-MM-DD')
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
        <div class="table-responsive pt-2 px-2" id="dataFaskesPerujuk">
            <table class="table table-bordered table-hover dataTable dtr-inline" id="faskesPerujukTable"
                cellspacing="0">
                <thead class="bg bg-orange table-bordered">
                    <tr>
                        <th>NO</th>
                        <th>Nama Faskes</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody class="table-bordered">
                </tbody>
            </table>
        </div>
    </div>
</div>
