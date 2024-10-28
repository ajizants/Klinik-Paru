                {{-- input farmasi --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 class="m-0 font-weight-bold text-dark text-center">Transaksi</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2">
                            <div class="container-fluid">
                                <div class="card card-lime">
                                    <div class="card-header">
                                        <h4 class="card-title">Identitas</h4>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    @csrf
                                    <form class="form-horizontal">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="norm"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="norm" id="norm"
                                                        class="form-control" placeholder="No RM" maxlength="6"
                                                        pattern="[0-9]{6}" required />
                                                </div>
                                                <label for="layanan"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="layanan"
                                                        class="form-control bg-white border border-white "
                                                        placeholder="Layanan" readonly />
                                                </div>
                                                <label for="nama"
                                                    class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                                                    :</label>
                                                <div class="col-sm-5">
                                                    <input type="text" id="nama"
                                                        class="form-control bg-white border border-white "
                                                        placeholder="Nama Pasien" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tgltind"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="tgltind"
                                                        class="form-control bg-white border border-white "
                                                        placeholder="Tanggal" readonly />
                                                    <input type="text" id="tgltrans"
                                                        class="form-control bg-white border border-white "
                                                        placeholder="tgltrans" readonly hidden />
                                                </div>
                                                <label for="notrans"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="notrans"
                                                        class="form-control bg-white border border-white "
                                                        placeholder="Nomor Transaksi" readonly />
                                                </div>
                                                <label for="alamat"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                                    :</label>
                                                <div class="col-sm-5">
                                                    <textarea id="alamat" class="form-control bg-white border border-white " style="height: 69px;"
                                                        placeholder="Alamat Pasien" readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="mt-3 form-group row d-flex justify-content-center">
                                                <label for="apoteker"
                                                    class="col-sm-1 col-form-label font-weight-bold">Petugas
                                                    :</label>
                                                <div class="col-sm-4">
                                                    <select id="apoteker"
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
                            </div>
                            <div class="container-fluid">
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h4 class="card-title" id="inputSection">Input Obat</h4>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    @csrf
                                    <form class="form-horizontal">
                                        <div class="container-fluid mt-3 mx-0 d-flex justify-content-center">
                                            <div class="col-10 bg-info rounded py-2">
                                                <label for="obat"class="col-form-label"><b>Obat
                                                        :</b></label>
                                                <select id="obat"
                                                    class="form-control select2bs4 border border-primary">
                                                    <option value="">--Pilih obat--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="container-fluid mt-3 mx-0 d-flex justify-content-center row">

                                            <div class="col-sm-2 border border-danger rounded pb-1 m-1">
                                                <label for="productID" class="col-form-label"><b>ID Produk</b></label>
                                                <input type="text"id="productID"
                                                    class="form-control  border border-info" placeholder="ID Produk">
                                            </div>
                                            <div class="col-sm-2 border border-danger rounded pb-1 m-1">
                                                <label for="qty" class="col-form-label"><b>Jumlah
                                                        :</b></label>
                                                <input type="text"id="qty" class="form-control  border border-info"
                                                    placeholder="Jumlah">
                                            </div>
                                            <div class="col-sm-2 border border-danger rounded pb-1 m-1">
                                                <label for="jual" class="col-form-label"><b>Harga
                                                        Jual:</b></label>
                                                <input type="text"id="jual"
                                                    class="form-control  border border-info" placeholder="Harga Jual">
                                            </div>
                                            <div class="col-sm-2 border border-danger rounded pb-1 m-1">
                                                <label for="beli" class="col-form-label"><b>Harga Beli
                                                        :</b></label>
                                                <input type="text"id="beli"
                                                    class="form-control  border border-info" placeholder="Harga Beli"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-2 border border-danger rounded pb-1 m-1">
                                                <label for="total" class="col-form-label"><b>Total Harga
                                                        :</b></label>
                                                <input type="text"id="total"
                                                    class="form-control  border border-info" placeholder="Total Harga"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="container mt-3" id="add">
                                            <a id="addFarmasi"
                                                class="btn btn-success d-flex justify-content-center mb-4">+
                                                Transaksi Obat</a>
                                        </div>
                                        <div class="container mt-3" id="edit">
                                            <a id="editFarmasi"
                                                class="btn btn-warning d-flex justify-content-center mb-4">Update
                                                Transaksi Obat</a>
                                        </div>
                                        <!-- /.card-body -->
                                    </form>
                                </div>
                            </div>
                            <div class="container-fluid row">
                                <div class="card card-warning col">
                                    <div class="card-header">
                                        <h4 class="card-title">Data
                                            Transaksi Obat Farmasi</h4>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="dataFarmasi" name="dataFarmasi" class="table table-striped"
                                            style="width:100%" cellspacing="0">
                                            <thead class="bg-secondary">
                                                <tr>
                                                    <th class="no-total" width="35px">Aksi</th>
                                                    <th class="col-1 text-center">No</th>
                                                    <th class="col-1">RM</th>
                                                    <th class="col-4">Obat</th>
                                                    <th class="">Qty</th>
                                                    <th class="no-total">Total</th>
                                                    {{-- <th class="col-3">Petugas</th>
                                                    <th class="col-3">Dokter</th> --}}
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="loadingSpinner" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                                {{-- </div>
                            <div class="container-fluid"> --}}
                                <div class="card card-lime col">
                                    <div class="card-header">
                                        <h4 class="card-title">Data
                                            Transaksi Obat & BMHP IGD</h4>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="dataIGD" name="dataIGD" class="table table-striped"
                                            style="width:100%" cellspacing="0">
                                            <thead class="bg-fuchsia">
                                                <tr>
                                                    <th class="no-total" width="35px">Aksi</th>
                                                    <th class="col-1 text-center">No</th>
                                                    <th class="col-1">RM</th>
                                                    <th class="col-3">Obat</th>
                                                    <th class="">Qty</th>
                                                    <th class="no-total">Total</th>
                                                    {{-- <th class="col-3">Petugas</th>
                                                    <th class="col-3">Dokter</th> --}}
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="loadingSpinner" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="container-fluid mb-4">
                        <div class="form-row d-flex justify-content-end">
                            <div class="col-md-2 d-flex justify-content-end d-flex align-items-center">
                                <label for="tagihan" class="form-label mb-0"><b>Total
                                        Tagihan :</b></label>
                            </div>
                            <div class="col-md-2">
                                <input type="text"id="tagihan" class="form-control border border-info"
                                    placeholder="Total Tagihan" readonly>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-danger" id="tblBatal">Batal</a>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-success" id="tblSimpan">Selesai</a>
                            </div>
                        </div>
                    </div>
                </div>
