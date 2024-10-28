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
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h4 class="card-title">Identitas</h4>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                @csrf
                                <form class="form-horizontal" id="formIdentitas">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="norm"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">No RM
                                                :</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="norm" id="norm" class="form-control"
                                                    placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required
                                                    onkeyup="handleKeyUp(event)" />
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
                                        <div class="form-group row mt-3">
                                            <label for="tglKunj"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                                :</label>
                                            <div class="col-sm-2">
                                                <input type="date" id="tglKunj" class="form-control bg-white" />
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
                                        <div class="mt-3 form-group row d-flex justify-content-center">
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
                            {{-- <fieldset> --}}
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Input Kunjungan Pasien TBC</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <div class="container-fluid p-3 card-body">
                                    @csrf
                                    <form class="row g-3 d-flex justify-content-center">
                                        {{-- <div class="col-md-3">
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
                                            </div> --}}
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
                                                placeholder="Jumlah" value="1">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="total" class="form-label">Total Harga:</label>
                                            <input type="text"id="total" class="form-control border border-info"
                                                placeholder="Total Harga" readonly>
                                        </div>
                                        <div class="col-10">
                                            <a id="addFarmasi"
                                                class="btn btn-success d-flex justify-content-center mb-4">+
                                                Transaksi Layanan</a>
                                        </div>
                                    </form>
                                </div>
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
                        {{-- </fieldset> --}}
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
