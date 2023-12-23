                {{-- input tindakan --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Asuhan Keperawatan</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body">
                            @csrf
                            <fieldset>
                                <div class="form-grup row border border-3 border-info mx-1">
                                    <h5 class="border-bottom border-3 border-info py-2">Identitas</h5>
                                    <div class="row">
                                        <div class="col-md-3 form-inline">
                                            <label for="norm" class="font-weight-bold ">No RM
                                                :</label>
                                            <input type="text" name="norm" id="norm"
                                                class="form-control col-4 ml-2" placeholder="No RM" maxlength="6"
                                                pattern="[0-9]{6}" required />
                                        </div>
                                        <div class="col-md-4 form-inline">
                                            <label for="nama" class="font-weight-bold ">Nama
                                                :</label>
                                            <input type="text" id="nama" class="form-control-plaintext col ml-2"
                                                placeholder="Nama Pasien" readonly></input>
                                        </div>
                                        <div class="col-md-5 form-inline">
                                            <label for="alamat" class="font-weight-bold ">Alamat
                                                :</label>
                                            <input id="alamat" class="form-control-plaintext col ml-2"
                                                placeholder="Alamat Pasien" readonly></input>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3 form-inline">
                                            <label for="layanan" class="form-label font-weight-bold ">Layanan
                                                :</label>
                                            <input type="text" id="layanan" class="form-control-plaintext col ml-2"
                                                placeholder="Layanan" readonly />
                                        </div>
                                        <div class="col-md-4 form-inline">
                                            <label for="tgltind" class="font-weight-bold">Tanggal
                                                :</label>
                                            <input type="text" id="tgltind" class="form-control-plaintext col ml-2"
                                                placeholder="Tanggal" readonly />
                                        </div>
                                        <div class="col-md-5 form-inline">
                                            <label for="notrans" class=" form-label font-weight-bold ">Transaksi
                                                :</label>
                                            <input type="text" id="notrans"
                                                class="form-control-plaintext  col-sm-3 ml-2"
                                                placeholder="Nomor Transaksi" readonly />
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class=" col form-inline">
                                            <label for="petugas" class="form-label font-weight-bold">Petugas</label>
                                            <select id="petugas" class="select2bs4">
                                                <option value="">--Pilih Petugas--</option>
                                            </select>
                                        </div>
                                        <div class="col form-inline">
                                            <label for="dokter" class="form-label font-weight-bold">Dokter :</label>
                                            <select id="dokter" class="select2bs4 mb-3 border border-primary">
                                                <option value="">--Pilih Dokter--</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <br>
                        </div>
                        <div class="container-fluid mb-4">
                            <div class="form-row d-flex justify-content-end">
                                <div class="col-auto">
                                    <a class="btn btn-danger" id="tblBatal">Batal</a>
                                </div>
                                <div class="col-auto">
                                    <a class="btn btn-success" id="tblSimpan">Selesai</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
