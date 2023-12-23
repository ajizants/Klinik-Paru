                {{-- input tindakan --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Farmasi</h4>
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
                                            <label for="notrans" class=" form-label font-weight-bold ">NoTran
                                                :</label>
                                            <input type="text" id="notrans"
                                                class="form-control-plaintext  col-sm-3 ml-2"
                                                placeholder="Nomor Transaksi" readonly />
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class=" col form-inline">
                                            <label for="kasir" class="form-label font-weight-bold">Petugas
                                                Kasir</label>
                                            <select id="kasir" class="select2bs4">
                                                <option value="Nasirin">Nasirin</option>
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
                            <fieldset>
                                <div class="form-group">
                                    <div class="form-grup border border-3 border-info">
                                        <h5 class="border-bottom border-3 border-info p-2">Input Layanan
                                        </h5>
                                        @csrf
                                        <form class="row g-3 d-flex justify-content-center">
                                            <div class="col-md-3">
                                                <label for="kelas"> kelas :</label>
                                                <select id="kelas" class="select2bs4 border border-primary">
                                                    <option value="">--Pilih kelas--</option>
                                                    <option value="1">Rajal</option>
                                                    <option value="2">Dokter</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="jenislayanan"> Layanan :</label>
                                                <select id="jenislayanan" class="select2bs4 border border-primary">
                                                    <option value="">--Pilih Layanan--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="beli" class="form-label">Tarif :</label>
                                                <input type="text"id="beli" class="form-control border border-info"
                                                    placeholder="Harga Beli" readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="qty" class="form-label">Jumlah</label>
                                                <input type="text"id="qty" class="form-control border border-info"
                                                    placeholder="Jumlah">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="total" class="form-label">Total Harga:</label>
                                                <input type="text"id="total"
                                                    class="form-control border border-info" placeholder="Total Harga"
                                                    readonly>
                                            </div>
                                            <div class="col-10">
                                                <a id="addFarmasi"
                                                    class="btn btn-success d-flex justify-content-center mb-4">+
                                                    Transaksi Layanan</a>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col  border border-3 border-info mt-2">
                                        <h5 class="text-center border-bottom border-3 border-info py-2">Data
                                            Tagihan Layanan Pasien</h5>
                                        <div class="table-responsive pt-2 px-2">
                                            <table id="tagihan" name="tagihan" class="table table-striped"
                                                style="width:100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th class="">Aksi</th>
                                                        <th class="">No</th>
                                                        <th class="">No RM</th>
                                                        <th class="col-3">Layanan</th>
                                                        <th class="">Jml</th>
                                                        <th class="col-3">Total Harga</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div id="loadingSpinner" style="display: none;">
                                            <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="container-fluid mb-4 row">
                            <div class="col-md-2">
                                <label for="totaltagihan" class="form-label">Total Tagihan :</label>
                                <input type="text"id="total" class="form-control border border-info"
                                    placeholder="Total Tagihan" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="bayar" class="form-label">Bayar:</label>
                                <input type="text"id="bayar" class="form-control border border-info"
                                    placeholder="Bayar">
                            </div>
                            <div class="col-md-2">
                                <label for="kembali" class="form-label">Kembalian :</label>
                                <input type="text"id="kembali" class="form-control border border-info"
                                    placeholder="Kembalian">
                            </div>
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
