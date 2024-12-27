<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 id="antrianSection" class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show collapse show card-body p-0" id="collapseCardAntrian">
        <div class="table-responsive">
            <div id="loadingSpinner" style="display: none;"
                class="badge bg-primary text-wrap text-center z-3 position-absolute mt-5">
                <i class="fa fa-spinner fa-spin"> </i>Sedang Mencari data...
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link active bg-blue"
                        onclick=" toggleSections('#dTunggu')"><b>Tunggu</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick=" toggleSections('#dSelesai')"><b>Selesai</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick=" toggleSections('#dAntrian')"><b>Antrian
                            All</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick=" toggleSections('#dSkip')"><b>Skip</b></a>
                </li>
                <div class="input-group col d-flex justify-content-start ml-5">
                    <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal"
                        value="{{ old('date') }}" required onchange="antrianFar();">
                    <div class="input-group-addon btn btn-danger">
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian" onclick="antrianFar();"></span>
                    </div>
                </div>
            </ul>
            @include('Template.Table.selesai')
            @include('Template.Table.all')
            <div id="dTunggu" class="card-body card-body-hidden p-2">
                <h5 class="mb-0 text-center"><b>Daftar Tunggu</b></h5>
                <div class="table-responsive pt-2 px-2">
                    <table id="dataAntrian" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-primary">
                            <tr>
                                <th>Aksi</th>
                                <th>Status Pulang</th>
                                <th>Urut</th>
                                <th>Wkatu Masuk</th>
                                <th>NoRM</th>
                                <th>Penjamin</th>
                                <th>Nama Pasien</th>
                                <th>Dokter</th>
                                <th>Status Kasir</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="dSkip" class="card-body card-body-hidden p-2" style="display: none;">
                <h5 class="mb-0 text-center"><b>Daftar Skip</b></h5>
                <div class="table-responsive pt-2 px-2">
                    <table id="dataSkip" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%;"
                        cellspacing="0">
                        <thead class="bg bg-primary">
                            <tr>
                                <th>Aksi</th>
                                <th>Status Pulang</th>
                                <th>Urut</th>
                                <th>Wkatu Masuk</th>
                                <th>NoRM</th>
                                <th>Penjamin</th>
                                <th>Nama Pasien</th>
                                <th>Dokter</th>
                                <th>Status Kasir</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
