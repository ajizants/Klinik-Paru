                <div class="container-fluid">
                    <div class="card card-lime">
                        <div class="card-header">
                            <h4 class="card-title">Identitas</h4>
                        </div>
                        @csrf
                        <form class="form-horizontal" id="form_identitas">
                            <div class="card-body" id="inputSection">
                                <div class="form-grup row">
                                    <label for="norm" class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                        :</label>
                                    <div class="col-sm-2 input-group" style="overflow: hidden;">
                                        <input type="text" name="norm" id="norm" class="form-control"
                                            placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required
                                            onkeyup="enterCariRM(event,'lab',this.value);" />
                                    </div>
                                    <label for="layanan" class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="layanan" class="form-control bg-white"
                                            placeholder="Layanan" readonly />
                                    </div>
                                    <label for="nama" class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                                        :</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="nama" class="form-control bg-white"
                                            placeholder="Nama Pasien" readonly>
                                    </div>
                                </div>
                                <div class="form-grup row mt-2">
                                    <label for="tgltrans" class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="date" id="tgltrans" class="form-control bg-white"
                                            placeholder="Tanggal Transaksi" />
                                    </div>
                                    <label for="notrans" class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="notrans" class="form-control bg-white"
                                            placeholder="Nomor Transaksi" readonly required />
                                    </div>
                                    <label for="alamat" class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                        :</label>
                                    <div class="col-sm-5">
                                        <input id="alamat" class="form-control bg-white" placeholder="Alamat Pasien"
                                            readonly />
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="container-fluid">
                    @csrf
                    <div class="col p-0">
                        <div class="card card-success">
                            <div class="card-header">
                                <h4 class="card-title">Input Hasil Pemeriksaan Laboratorium</h4>
                            </div>
                            <div class="card-body py-1">
                                <table id="inputHasil" class="table table-tight">
                                    <thead>
                                        <tr>
                                            {{-- <th>Aksi</th> --}}
                                            <th>NO</th>
                                            <th>NO RM</th>
                                            <th>Pemeriksaan</th>
                                            <th>Petugas</th>
                                            <th>Hasil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer form-row d-flex justify-content-end">
                                <div class="col-auto">
                                    <a class="btn btn-success" id="tblSimpan" onclick="simpan();">Simpan</a>
                                </div>
                                <div class="col-auto">
                                    <a class="btn btn-danger" id="tblBatal"
                                        onclick="resetForm('dibatalkan');">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                    {{-- <form id="frmPetugas">
                                <div class="mx-2 form-grup row">
                                    <label for="sampling" class="col-sm-1 col-form-label font-weight-bold">Sampling
                                        :</label>
                                    <div class="col-sm-3">
                                        <select id="sampling" class="form-control select2bs4 border border-primary"
                                            required>
                                            <option value="">--Pilih Petugas--</option>
                                        </select>
                                        <label for="bakteri"
                                            class="col-sm-1 col-form-label font-weight-bold">Bakteriologi
                                            :</label>
                                        <div class="col-sm-3">
                                            <select id="bakteri"
                                                class="form-control select2bs4 mb-3 border border-primary" required>
                                                <option value="">--Pilih Petugas--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <label for="admin" class="col-sm-1 col-form-label font-weight-bold">Administrasi
                                        :</label>
                                    <div class="col-sm-3">
                                        <select id="admin"
                                            class="form-control select2bs4 mb-3 border border-primary" required>
                                            <option value="">--Pilih Petugas--</option>
                                        </select>
                                    </div>
                                    <label for="darah" class="col-sm-1 col-form-label font-weight-bold">Darah
                                        :</label>
                                    <div class="col-sm-3">
                                        <select id="darah" class="form-control select2bs4 border border-primary"
                                            required>
                                            <option value="">--Pilih Petugas--</option>
                                        </select>
                                    </div>
                                    <label for="imuno" class="col-sm-1 col-form-label font-weight-bold">Imuno
                                        :</label>
                                    <div class="col-sm-2">
                                        <select id="imuno" class="form-control select2bs4 border border-primary"
                                            required>
                                            <option value="">--Pilih Petugas--</option>
                                        </select>
                                    </div>
                                </div>
                            </form> --}}

                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
