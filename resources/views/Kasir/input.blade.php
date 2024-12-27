                {{-- input tindakan --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Transaksi</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2">
                            <div class="container-fluid">
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Identitas</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    @csrf
                                    <form class="form-horizontal"id="form_identitas">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="norm"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                                    :</label>
                                                <div class="col-sm-2 input-group" style="overflow: hidden;">
                                                    <input type="text" name="norm" id="norm"
                                                        class="form-control" placeholder="No RM" maxlength="6"
                                                        pattern="[0-9]{6}" required />
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
                                                <div class="col-sm-3">
                                                    <input type="text" id="nama" class="form-control bg-white"
                                                        placeholder="Nama Pasien" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <Select type="text" id="jk" class="form-control bg-white"
                                                        placeholder="JK">
                                                        <option value="">--JK--</option>
                                                        <option value="L">Laki-Laki</option>
                                                        <option value="P">Perempuan</option>
                                                    </Select>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-2">
                                                <label for="tgltrans"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="date" id="tgltrans" class="form-control bg-white"
                                                        placeholder="Tanggal Transaksi" />
                                                </div>
                                                <label for="notrans"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="notrans" class="form-control bg-white"
                                                        placeholder="Nomor Transaksi" readonly required />
                                                </div>
                                                <label for="alamat"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                                    :</label>
                                                <div class="col-sm-3">
                                                    <input id="alamat" class="form-control bg-white"
                                                        placeholder="Alamat Pasien" readonly />
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" id="umur" class="form-control bg-white"
                                                        placeholder="Umur">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </form>
                                </div>
                            </div>
                            <div class="container-fluid" id="formLayanan">
                                <div class="form-group row">
                                    <div class="col-sm p-0 card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Input Layanan</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-2">
                                            @csrf
                                            <form class="form-group col">
                                                <table id="tabelPemeriksaan" class="table table-tight table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="pilih-semua"></th>
                                                            <th>Item Pemeriksaan</th>
                                                            <th>QTY</th>
                                                            <th>Harga</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <a id="addKasir"
                                                    class="btn btn-success d-flex justify-content-center mb-4"
                                                    onclick="validateAndSubmit();">Tambah
                                                    Tagihan</a>
                                            </form>
                                        </div>
                                        <!-- /.card-body-->
                                    </div>
                                    <div class="col-sm">
                                        <div class="p-0 ml-2 card card-warning">
                                            <div class="card-header">
                                                <h3 class="card-title">Data
                                                    Transaksi Layanan</h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body p-2">
                                                <div class="table-responsive">
                                                    <table id="dataTagihan" name="dataTagihan"
                                                        class="table table-striped table-tight" style="width:100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Aksi</th>
                                                                <th>No RM</th>
                                                                <th>Layanan</th>
                                                                <th>Tarif</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm p-0 ml-2 card card-danger">
                                            <div class="card-header">
                                                <h3 class="card-title">Pembayaran</h3>
                                            </div>
                                            <div class="card-body p-2">
                                                <form action="#" class="form-group col" id="form_pembayaran">
                                                    <div class="form-group form-row">
                                                        <div class="col-sm">
                                                            <label for="tagihan">Tagihan:</label>
                                                            <input type="text" id="tagihan"
                                                                class="form-control bg-white" placeholder="Tagihan" />
                                                        </div>
                                                        <div class="col-sm">
                                                            <label for="kembali">Kembali:</label>
                                                            <input type="text" id="kembali"
                                                                class="form-control bg-white" placeholder="Kembali"
                                                                readonly />
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-row">
                                                        <div class="col-sm">
                                                            <label for="bayar">Bayar:</label>
                                                            <input type="text" id="bayar"
                                                                class="form-control bg-white" placeholder="Bayar" />
                                                        </div>
                                                        <div class="col-sm">
                                                            <label for="petugas">Petugas :</label>
                                                            <select id="petugas" class="form-control select2">
                                                                <option value="">--Pilih Petugas--</option>
                                                                <option value="Nasirin">Nasirin</option>
                                                                <option value="Desiana Budi P.">Desiana Budi P.
                                                                </option>
                                                                <option value="Popy P.">Popy P.</option>
                                                                {{-- @foreach (collect($petugas)->sortBy('nama') as $item)
                                                                <!-- Convert to collection and sort by 'nama' -->
                                                                <option value="{{ $item->idPetugas }}">
                                                                    {{ $item->nmPetugas }}</option>
                                                            @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <script></script>
                                                </form>
                                                <div class="form-group form-row">
                                                    <div class="col-sm">
                                                        <a type="button" id="btnSimpan" class="btn btn-primary"
                                                            onclick="simpanTransaksi();">Simpan</a>
                                                        <a type="button" id="btnBatal" class="btn btn-secondary"
                                                            onclick="resetForm('Transaksi Dibatalkan');">Selesai/Batal</a>
                                                    </div>
                                                    <div class="col-sm" id="divPanggil">
                                                    </div>
                                                    <div class="col-sm" id="divHapus">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
