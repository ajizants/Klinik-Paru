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
                                    <form class="form-horizontal">
                                        <div class="card-body" id="frm-identitas">
                                            <div class="form-group row">
                                                <label for="norm"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                                    :</label>
                                                <div class="col-sm-2 input-group">
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
                                                <div class="col-sm-5">
                                                    <input type="text" id="nama" class="form-control bg-white"
                                                        placeholder="Nama Pasien" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-2">
                                                <label for="tgltind"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="date" id="tgltrans" class="form-control bg-white"
                                                        placeholder="tgltrans" />
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
                                        </div>
                                        <!-- /.card-body -->
                                    </form>
                                </div>
                            </div>
                            <div class="container-fluid" id="formtind">
                                <div class="form-group row">
                                    <div class="col-sm-3 p-0 card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Input Tindakan</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-2">
                                            @csrf
                                            <form class="form-group col">
                                                <div class="card-body p-1 card-body table-responsive" id="permintaan"
                                                    style="height: 10rem">
                                                </div>
                                                {{-- <textarea id="asktind" class="form-control-plaintext border border-primary px-2 fs-6" style="height: 8rem"
                                                    placeholder="Permintaan Tindakan" readonly></textarea> --}}
                                                <div class="form-group">
                                                    <label for="tindakan"> Tindakan :</label>
                                                    <select id="tindakan"
                                                        class="select2bs4 form-control border border-primary">
                                                        <option value="">--Pilih Tindakan--</option>
                                                        @foreach (collect($tindakan)->sortBy('nama') as $item)
                                                            <!-- Convert to collection and sort by 'nama' -->
                                                            <option value="{{ $item->kdTind ?? $item->idLayanan }}">
                                                                {{ $item->nmTindakan ?? $item->nmLayanan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="petugas">Pelaksana :</label>
                                                    <p id='petugasSebelumnya'>Bef:
                                                        {{ $dataIgd[0]['nama'] }}</p>
                                                    <select id="petugas"
                                                        class="select2bs4 form-control mb-3 border border-primary">
                                                        <option value="">--Pilih Petugas--</option>
                                                        @foreach (collect($perawat)->sortBy('nama') as $item)
                                                            <!-- Convert to collection and sort by 'nama' -->
                                                            <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                                                {{ $item->nama }} {{ $item->gelar_b }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dokter">Dokter :</label>
                                                    <select id="dokter"
                                                        class="select2bs4 form-control mb-3 border border-primary">
                                                        <option value="">--Pilih Dokter--</option>
                                                        @foreach (collect($dokter)->sortBy('nama') as $item)
                                                            <!-- Convert to collection and sort by 'nama' -->
                                                            <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                                                {{ $item->nama }} {{ $item->gelar_b }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <br>
                                                <a id="addTindakan"
                                                    class="btn btn-success d-flex justify-content-center mb-4"
                                                    onclick="addTindakan();">+
                                                    Tindakan</a>
                                            </form>
                                        </div>
                                        <!-- /.card-body-->
                                    </div>
                                    <div class="col-sm p-0 ml-2 card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Data
                                                Transaksi Tindakan</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-2">
                                            <div class="table-responsive">
                                                <table id="dataTindakan" name="dataTindakan"
                                                    class="table table-striped table-tight" style="width:100%"
                                                    cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-1">Aksi</th>
                                                            <th class="col-1">Status</th>
                                                            <th class="col-1">No RM</th>
                                                            <th class="col-3">Tindakan</th>
                                                            <th class="col-2">Petugas</th>
                                                            <th class="col-2">Dokter</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid" id="formbmhp" style="display:none;">
                                <div class="form-group row">
                                    <div class="col-sm-4 p-0 card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Input BMHP</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-2">
                                            @csrf
                                            <form class="form-group col">
                                                <div class="form-group col">
                                                    <label for="bmhp"> BMHP :</label>
                                                    <select id="bmhp" onchange="setHarga();"
                                                        class="bmhp form-control border border-primary">
                                                        <option value="">--Pilih BMHP--</option>
                                                        @foreach (collect($bmhp)->sortBy('nmObat') as $item)
                                                            <option prdukid="{{ $item->product_id }}"
                                                                jual="{{ $item->hargaJual }}"
                                                                value="{{ $item->id }}">{{ $item->nmObat }}
                                                                ---( Sisa Stok: {{ $item->sisa }} )---
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <br>
                                                    <div class="input-group d-flex justify-content-center">
                                                        <a type="button" class="btn btn-danger"
                                                            id="decreaseBtn">-</a>
                                                        <input type="text"id="qty"
                                                            class="form-control col-5 border border-primary text-center"
                                                            placeholder="Jumlah"
                                                            oninput="hitungTotalHarga(this.value);">
                                                        <a type="button" class="btn btn-success"
                                                            id="increaseBtn">+</a>
                                                    </div>
                                                    <br>
                                                    <div class="input-group d-flex justify-content-center">
                                                        <input type="text"id="productID"
                                                            class="form-control col-5 border border-primary text-center rounded"
                                                            placeholder="Produk ID"readonly>
                                                        <input type="text"id="jual"
                                                            class="form-control col-5 border border-primary text-center"
                                                            placeholder="Harga Jual"readonly>
                                                        <input type="text"id="total"
                                                            class="form-control col-5 border border-primary text-center"
                                                            placeholder="Total Harga"readonly>
                                                    </div>
                                                    <br>
                                                    <a id="addBMHP"
                                                        class="btn btn-success d-flex justify-content-center mb-4">Tambah
                                                        BMHP</a>
                                                    <a id="addBMHPSelesai"
                                                        class="btn btn-primary d-flex justify-content-center mb-4">Input
                                                        BMHP Tindakan Lainnya</a>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.card-body-->
                                    </div>
                                    <div class="col-sm p-0 ml-2 card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Data Transaksi
                                                BMHP</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-center row">
                                                {{-- @csrf
                                                <form class="row"> --}}
                                                <div class="form-group mx-sm-1 mb-1 col-5">
                                                    <input type="text" id="modaltindakan" class="form-control"
                                                        placeholder="tindakan" readonly>
                                                </div>
                                                <div class="form-group mx-sm-1 mb-1 col-3">
                                                    <input type="text" id="modalpetugas" class="form-control"
                                                        placeholder="petugas" readonly>
                                                </div>
                                                <div class="form-group mx-sm-1 mb-1 col-3">
                                                    <input type="text" id="modaldokter" class="form-control"
                                                        placeholder="dokter" readonly>
                                                </div>
                                                <div class="form-group ">
                                                    <input type="text" id="modalidTind" class="form-control"
                                                        placeholder="idTind" hidden readonly>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" id="modalkdTind" class="form-control"
                                                        placeholder="kdTind" hidden readonly>
                                                </div>
                                                <div class="form-group mx-sm-1 mb-1 col">
                                                    <input type="text" id="modalnorm" class="form-control col"
                                                        placeholder="norm" hidden readonly>
                                                </div>
                                                {{-- </form> --}}

                                            </div>
                                            <div class="table-responsive pt-2 px-2">
                                                <table id="transaksiBMHP" name="dataBMHP" class="table table-striped"
                                                    style="width:100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="">Aksi</th>
                                                            <th class="">No</th>
                                                            <th class="">BMHP</th>
                                                            <th class="col-3">Jumlah</th>
                                                            <th class="col-3">Total Harga</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            @include('Template.Table.loading')
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-row d-flex justify-content-end">
                                <div class="col-auto">
                                    <a class="btn btn-danger" id="tblBatal" onclick="batal();">Batal</a>
                                </div>
                                <div class="col-auto">
                                    <a class="btn btn-success" id="tblSimpan" onclick="selesai();">Selesai</a>
                                </div>
                            </div> --}}

                            <div class="form-row d-flex justify-content-betwen">
                                <div class="col-6">
                                    <a type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#spirometriModal" onclick="setSpiroForm();">Input Hasil
                                        Spirometri</a>
                                </div>
                                <div class="col-6 form-row d-flex justify-content-end">
                                    <div class="col-auto">
                                        <a class="btn btn-danger" id="tblBatal" onclick="batal();">Batal</a>
                                    </div>
                                    <div class="col-auto">
                                        <a class="btn btn-success" id="tblSimpan" onclick="selesai();">Selesai</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
