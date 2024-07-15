                {{-- input Dots Center --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Transaksi</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2">
                            <div class="container-fluid d-flex justify-content-center my-2">
                                <button type="button" class="col btn btn-danger" id="modal-Ftb" data-toggle="modal"
                                    data-target="#modal-pasienTB">
                                    <b>+ Tambah Pasien Baru Pengobatan TBC</b>
                                </button>
                            </div>
                            <div class="container-fluid">
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h4 class="card-title">Identitas</h4>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    @csrf
                                    <form class="form-horizontal" id="formIdentitas">
                                        <div class="card-body">
                                            <div class="form-grup row">
                                                <label for="norm"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">No RM
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="norm" id="norm"
                                                        class="form-control" placeholder="No RM" maxlength="6"
                                                        pattern="[0-9]{6}" required
                                                        onkeyup="enterCariRM(event,'dots');" />
                                                </div>
                                                <label for="layanan"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="layanan" class="form-control bg-white"
                                                        placeholder="Layanan" readonly />
                                                </div>
                                                <label for="nama"
                                                    class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                                                    :</label>
                                                <div class="col-sm-5">
                                                    <input type="text" id="nama" class="form-control bg-white"
                                                        placeholder="Nama Pasien" readonly>
                                                </div>
                                            </div>
                                            <div class="form-grup row mt-3">
                                                <label for="tglKunj"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="date" id="tglKunj"
                                                        class="form-control bg-white" />
                                                    <input type="text" id="tgltind" class="form-control bg-white"
                                                        placeholder="tgltind" readonly hidden />
                                                </div>
                                                <label for="notrans"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="notrans" class="form-control bg-white"
                                                        placeholder="Nomor Transaksi" readonly />
                                                </div>
                                                <label for="alamat"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                                    :</label>
                                                <div class="col-sm-5">
                                                    <input id="alamat" class="form-control bg-white"
                                                        placeholder="Alamat Pasien" readonly />
                                                </div>
                                            </div>
                                            <div class="mt-3 form-grup row d-flex justify-content-center">
                                                <label for="petugas"
                                                    class="col-sm-1 col-form-label font-weight-bold">Petugas
                                                    :</label>
                                                <div class="col-sm-4">
                                                    <select id="petugas"
                                                        class="form-control select2bs4 border border-primary">
                                                        <option value="">--Pilih Petugas--</option>
                                                    </select>
                                                </div>
                                                <label for="dokter"
                                                    class="col-sm-1 col-form-label font-weight-bold">Dokter
                                                    :</label>
                                                <div class="col-sm-4">
                                                    <select id="dokter"
                                                        class="form-control select2bs4 mb-3 border border-primary">
                                                        <option value="">--Pilih Dokter--</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </form>
                                </div>

                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Input Kunjungan Pasien TBC</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <div class="container-fluid p-3 card-body">
                                        <div class="form-group row">
                                            <div class="col-sm-3 p-0 card card-warning">
                                                <div class="card-header">
                                                    <h3 class="card-title">Form Input</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body p-2">
                                                    @csrf
                                                    <form class="form-grup col" id="formKunjungan">
                                                        <div class="form-group">
                                                            <label for="bta"> Hasil
                                                                BTA</label>
                                                            <select id="bta"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="Tidak Cek BTA">--Pilih Hasil--</option>
                                                                <option value="negatif">Negatif</option>
                                                                <option value="+1">Positif 1</option>
                                                                <option value="+2">Positif 2</option>
                                                                <option value="+3">Positif 3</option>
                                                                <option value="+1-9">Positif 1-9</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="blnKe">
                                                                Pengobatan Bulan Ke
                                                            </label>
                                                            <select id="blnKe"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="">--Pilih Kemajuan--</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nxKontrol"> Kontrol Selanjutnya :</label>
                                                            <input id="nxKontrol" type="date"
                                                                class="form-control-sm col border border-primary" />
                                                        </div>
                                                        <div class="form-group" id="obatD">
                                                            <label for="obatDots">
                                                                Obat </label>
                                                            <select id="obatDots"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="">--Jenis Obat--</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="bb"> Berat
                                                                Badan</label>
                                                            <input type="text" id="bb"
                                                                class="form-control-sm col border border-primary"
                                                                placeholder="Berat Badan" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="ket">
                                                                Keterangan</label>
                                                            <textarea type="text" id="ket" class="form-control border border-primary" placeholder="Keterangan Lain"
                                                                required></textarea>
                                                        </div>
                                                        <br>
                                                        <button type="button"
                                                            class="btn btn-success d-flex justify-content-center mb-4"
                                                            onclick="validasiKunjungan();">Simpan
                                                            Kunjungan</button>
                                                    </form>
                                                </div>
                                                <!-- /.card-body-->
                                            </div>
                                            <div class="col-sm p-0 ml-2 card card-warning">
                                                <div class="card-header">
                                                    <h3 class="card-title">Data
                                                        Transaksi Dots Center</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body p-2">
                                                    <div class="table-responsive">
                                                        <div id="loadingSpinner" style="display: none;"
                                                            class="badge bg-primary text-wrap text-center z-3 position-absolute mt-5">
                                                            <i class="fa fa-spinner fa-spin"></i> Sedang Mencari
                                                            data...
                                                        </div>
                                                        <table id="kunjDots" name="kunjungan Dots Center"
                                                            class="table table-striped" style="width:100%"
                                                            cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-1">Aksi</th>
                                                                    <th class="col-1">No RM</th>
                                                                    <th class="col-1">Tgl Kunjungan</th>
                                                                    <th class="col-1">Bln Ke</th>
                                                                    <th>BTA</th>
                                                                    <th>BB</th>
                                                                    <th>Terapi</th>
                                                                    <th class="col-3">Petugas</th>
                                                                    <th class="col-3">Dokter</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                        </div>
                                        <div class="form-row d-flex justify-content-end">
                                            <div class="col-auto">
                                                <a class="btn btn-danger" id="tblBatal" onclick="batal();">Batal</a>
                                            </div>
                                            <div class="col-auto">
                                                <a class="btn btn-success" id="tblSimpan"
                                                    onclick="selesai();">Selesai</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
