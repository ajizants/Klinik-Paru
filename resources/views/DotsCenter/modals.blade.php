                <div class="modal fade" id="modal-pasienTB">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Form Tambah Pasien TBC Baru</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body p-2">
                                    <div class="container-fluid">
                                        <div class="card card-black">
                                            <!-- form start -->
                                            @csrf
                                            <form class="form-horizontal">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for="modal-norm"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">No
                                                            RM</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="modal-norm" id="modal-norm"
                                                                class="form-control" placeholder=" No RM" maxlength="6"
                                                                pattern="[0-9]{6}" required />
                                                        </div>

                                                        <label for="modal-layanan"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" id="modal-layanan"
                                                                class="form-control bg-white border border-white"
                                                                placeholder="Layanan" readonly />
                                                        </div>

                                                        <label for="modal-nama"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">Nama</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" id="modal-nama"
                                                                class="form-control bg-white border border-white"
                                                                placeholder="Nama Pasien" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-3">
                                                        <label for="modal-hp"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">No
                                                            HP</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" id="modal-hp"
                                                                class="form-control bg-white" />
                                                        </div>

                                                        <label for="modal-status"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">Status</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" id="modal-status"
                                                                class="form-control bg-white border border-white" />
                                                        </div>

                                                        <label for="modal-alamat"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat</label>
                                                        <div class="col-sm-5">
                                                            <textarea id="modal-alamat" class="form-control bg-white border border-white" style="height: 69px;"
                                                                placeholder="Alamat Pasien" readonly></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="modal-dokter"
                                                            class="col-sm-1 col-form-label font-weight-bold">Dokter</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-dokter"
                                                                class="form-control select2bs4 mb-3 border border-primary">
                                                                <option value="">--Pilih Dokter--</option>
                                                            </select>
                                                        </div>

                                                        <label for="modal-petugas"
                                                            class="col-sm-1 col-form-label font-weight-bold">Petugas</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-petugas"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="">--Pilih Petugas--</option>
                                                            </select>
                                                        </div>

                                                        <label for="modal-kdDx"
                                                            class="col-sm-1 col-form-label font-weight-bold">DX
                                                            Medis</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-kdDx"
                                                                class="form-control select2bs4 mb-3 border border-primary">
                                                                <option value="">--Pilih Diagnosa--</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="modal-tcm"
                                                            class="col-sm-1 col-form-label font-weight-bold">Hasil
                                                            TCM</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-tcm"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Pilih Hasil--</option>
                                                                <option value="Low RifSen">MTB Det Low RifSen</option>
                                                                <option value="Low RifRes">MTB Det Low RifRes</option>
                                                                <option value="Medium RifSen">MTB Det Medium RifSen
                                                                </option>
                                                                <option value="Medium RifRes">MTB Det Medium RifRes
                                                                </option>
                                                                <option value="Hight RifSen">MTB Det Hight RifSen
                                                                </option>
                                                                <option value="Hight RifRes">MTB Det Hight RifRes
                                                                </option>
                                                                <option value="Neg">Negative</option>
                                                            </select>
                                                        </div>

                                                        <label for="modal-hiv"
                                                            class="col-sm-1 col-form-label font-weight-bold">Status
                                                            HIV</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-hiv"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Pilih Status--</option>
                                                                <option value="Positif">Positif</option>
                                                                <option value="Negatif">Negatif</option>
                                                                <option value="Tidak Diketahui">Tidak Diketahui
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <label for="modal-dm"
                                                            class="col-sm-1 col-form-label font-weight-bold">Status
                                                            DM</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-dm"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Pilih Status--</option>
                                                                <option value="Positif">Positif</option>
                                                                <option value="Negatif">Negatif</option>
                                                                <option value="Tidak Diketahui">Tidak Diketahui
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-3">
                                                        <label for="modal-bb"
                                                            class="col-sm-1 col-form-label font-weight-bold">BB</label>
                                                        <div class="col-sm-3">
                                                            <input type="text" id="modal-bb"
                                                                class="form-control border border-info"
                                                                placeholder="Berat Badan" required />
                                                        </div>

                                                        <label for="modal-tglmulai"
                                                            class="col-sm-1 col-form-label font-weight-bold">Tgl
                                                            Mulai</label>
                                                        <div class="col-sm-3">
                                                            <input id="modal-tglmulai" type="date"
                                                                class="form-control border border-primary" />
                                                        </div>

                                                        <label for="modal-obtDots"
                                                            class="col-sm-1 col-form-label font-weight-bold">Obat</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-obtDots"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Jenis Obat--</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-3">
                                                        <div class="col-sm">
                                                            <textarea type="text" id="modal-ket" class="form-control border border-info" placeholder="Keterangan Lain"
                                                                style="height: 50px;" required></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-3">
                                                        <div class="col-sm-12">
                                                            <a id="addPTB"
                                                                class="btn btn-success d-flex justify-content-center">Simpan
                                                                Data Pasien</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>

                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Data Pasien TBC Baru Hari Ini</h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <!-- form start -->
                                            <div class="card-body p-2">
                                                <div class="table-responsive">
                                                    <table id="modal-Ptb"
                                                        class="table table-striped table-hover pt-0 mt-0 fs-6"
                                                        style="width:100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th width="15px">Aksi</th>
                                                                <th width="35px">Mulai</th>
                                                                <th width="15px"class="text-center">No</th>
                                                                <th width="15px" class="text-center">NoRM</th>
                                                                <th width="15px"class="text-center">No HP</th>
                                                                <th width="36px"class="text-center">Status</th>
                                                                <th width="">Nama</th>
                                                                <th width="">Alamat</th>
                                                                <th width="">Dokter</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                </body>

                </html>
