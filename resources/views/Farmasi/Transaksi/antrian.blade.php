<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 id="antrianSection" class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show card-body p-0" id="collapseCardAntrian">
        @include('Template.Table.loading')
        <div class="table-responsive">
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
                <div class="input-group col-4 d-flex justify-content-start ml-5">
                    <input type="date" class="form-control col-sm-4 col-md-4 bg bg-warning" id="tanggal"
                        value="{{ old('date') }}" required onchange="antrianFar();">
                    <div class="input-group-addon btn btn-danger" style="height: 38.22222px;">
                        <i class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian" onclick="antrianFar();"></i>
                    </div>
                </div>
                <div class="input-group col d-flex justify-content-start ml-5 bg-danger">
                    <label for="tanggal_bpjs" class="col-form-label"> Tgl Resep:</label>
                    <input type="date" class="form-control col-sm-4 col-md-4 bg bg-warning" id="tanggal_bpjs"
                        value="{{ old('date') }}">
                    <input type="number" name="no_rm" id="no_rm" class="form-control col"
                        placeholder="Tulis No RM untuk cetak resep BPJS" />
                    <a onclick="cetakResepBpjs();" class="btn btn-success" data-toggle="tooltip" data-placement="right"
                        title="Cetak Resep BPJS">
                        <i class="fa-solid fa-print px-2"></i>
                    </a>
                    <script>
                        function cetakResepBpjs() {
                            const no_rm = $('#no_rm').val();
                            const tgl = $('#tanggal_bpjs').val();
                            //cek jika kosong
                            if (!no_rm || !tgl) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'No RM dan Tanggal Belum di isi...!!!',
                                });
                                return;
                            }
                            const url = `api/resep2/${no_rm}/${tgl}`;
                            window.open(url, '_blank');
                        }
                    </script>
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
