                    <ul class="nav nav-tabs py-2">
                        <li class="nav-item ml-1">
                            <a href="#" class="nav-link bg-olive active bg-white" id="ingudang"><b>Daftar
                                    Pembelian</b></a>
                        </li>
                        <li class="nav-item ml-1">
                            <a href="#" class="nav-link bg-fuchsia" id="igudang"><b>Stok Gudang</b></a>
                        </li>
                        <li class="nav-item ml-1">
                            <a href="#" class="nav-link bg-purple" id="ifarmasi"><b>Stok Farmasi</b></a>
                        </li>
                        <li class="nav-item ml-1">
                            <a href="#" class="nav-link bg-maroon" id="iigd"><b>Stok IGD</b></a>
                        </li>
                    </ul>
                    <div>
                        <div class="form-group col-auto p-0" id="inputdata">
                            <span><a href="#" id="input"
                                    class="btn btn-success d-flex justify-content-center">Input</a>
                            </span>
                        </div>
                    </div>


                    <div id="tabelData" class="pt-1">
                        <div class="card card-info" id="dfarmasi">
                            <div class="card-header">
                                <h3 class="card-title">Stok Obat Farmasi</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class=" border border-black">
                                        <div class="card-body card-body-hidden p-2">
                                            <table id="farmasiObat" class="table table-striped fs-6" style="width:100%"
                                                cellspacing="0">
                                                <thead class="table-secondary table-sm">
                                                    <tr>
                                                        <th>Aksi</th>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jenis</th>
                                                        <th>Pabrikan</th>
                                                        <th>Sediaan</th>
                                                        <th>Sumber</th>
                                                        <th>Suplier</th>
                                                        <th>Tgl Beli</th>
                                                        <th>Tgl Exp</th>
                                                        <th>Harga Beli</th>
                                                        <th>Harga Jual</th>
                                                        <th>Stok Awal</th>
                                                        <th>Masuk</th>
                                                        <th>Keluar</th>
                                                        <th>Stok Akhir</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div id="loadingSpinner" style="display: none;" class="text-center">
                                                <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="card card-info" id="dgudang">
                            <div class="card-header">
                                <h3 class="card-title">Stok Obat Gudang</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class=" border border-black">
                                        <div class="card-body card-body-hidden p-2">
                                            <table id="dgudangObat" class="table table-striped fs-6" style="width:100%"
                                                cellspacing="0">
                                                <thead class="table-secondary table-sm">
                                                    <tr>
                                                        <th>Aksi</th>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jenis</th>
                                                        <th>Pabrikan</th>
                                                        <th>Sediaan</th>
                                                        <th>Sumber</th>
                                                        <th>Suplier</th>
                                                        <th>Tgl Beli</th>
                                                        <th>Tgl Exp</th>
                                                        <th>Harga Beli</th>
                                                        <th>Harga Jual</th>
                                                        <th>Stok Awal</th>
                                                        <th>Masuk</th>
                                                        <th>Keluar</th>
                                                        <th>Stok Akhir</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div id="loadingSpinner" style="display: none;" class="text-center">
                                                <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="loadingSpinner" style="display: none;" class="text-center">
                                    <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                </div>
                            </form>
                        </div>
                        <div class="card card-info" id="dingudang">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Pembelian Obat Gudang</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class=" border border-black">
                                        <div class="card-body card-body-hidden p-2">
                                            <table id="gudangObat" class="table table-striped fs-6" style="width:100%"
                                                cellspacing="0">
                                                <thead class="table-secondary table-sm">
                                                    <tr>
                                                        <th>Aksi</th>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jenis</th>
                                                        <th>Pabrikan</th>
                                                        <th>Sediaan</th>
                                                        <th>Sumber</th>
                                                        <th>Suplier</th>
                                                        <th>Tgl Beli</th>
                                                        <th>Tgl Exp</th>
                                                        <th>Harga Beli</th>
                                                        <th>Harga Jual</th>
                                                        <th>Jumlah</th>
                                                        <th>Keluar</th>
                                                        <th>Stok Akhir</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div id="loadingSpinner" style="display: none;" class="text-center">
                                                <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="loadingSpinner" style="display: none;" class="text-center">
                                    <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                </div>
                            </form>
                        </div>
                        <div class="card card-info" id="digd">
                            <div class="card-header">
                                <h3 class="card-title">Stok Obat IGD</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class=" border border-black">
                                        <div class="card-body card-body-hidden p-2">
                                            <table id="igdObat" class="table table-striped fs-6" style="width:100%"
                                                cellspacing="0">
                                                <thead class="table-secondary table-sm">
                                                    <tr>
                                                        <th>Aksi</th>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jenis</th>
                                                        <th>Pabrikan</th>
                                                        <th>Sediaan</th>
                                                        <th>Sumber</th>
                                                        <th>Suplier</th>
                                                        <th>Tgl Beli</th>
                                                        <th>Tgl Exp</th>
                                                        <th>Harga Beli</th>
                                                        <th>Harga Jual</th>
                                                        <th>Stok Awal</th>
                                                        <th>Keluar</th>
                                                        <th>Stok Akhir</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div id="loadingSpinner" style="display: none;" class="text-center">
                                                <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="loadingSpinner" style="display: none;" class="text-center">
                                    <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card card-warning shadow mb-4" id="formInput"">
                        <div class="card-header">
                            <h3 class="card-title" id="adstok">Form Tambah Stok Obat</h3>
                            <h3 class="card-title" id="adobat">Form Tambah Pembelian Obat</h3>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal">
                                <div class="container-fluid rounded p-1 bg-teal color-palette">
                                    <label for="gObat" class="form-label pt-2"> Cari
                                        Obat </label>
                                    <button type="button" class="ml-4 rounded btn-primary" data-toggle="modal"
                                        data-target="#modal-Obat">Tambah Nama
                                        Obat</button>
                                    <div id="obatg">
                                        <select id="gObat" class="form-control select2bs4 border border-primary">
                                            <option value="">--- Pilih obat ---</option>
                                        </select>
                                    </div>
                                    <div id="obats">
                                        <select type="text" id="idObat"
                                            class="form-control select2bs4 border border-primary"
                                            placeholder="ID Obat">
                                            <option value="">--- Pilih obat ---</option>
                                        </select>
                                    </div>
                                    <input id="idGudang" class="form-control-sm col border border-primary"
                                        placeholder="Id Gudang" readonly hidden>
                                </div>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label for="productID" class="form-label pt-2">Produk ID</label>
                                        <div id="obatg">
                                            <input id="productID" class="form-control-sm col border border-primary"
                                                placeholder="Id Obat" readonly>
                                        </div>
                                        <label for="stokBaru" class="form-label pt-2"> Jumlah* </label>
                                        <div class="col p-0">
                                            <input type="text" id="stokBaru"
                                                class="form-control-sm col border border-primary"
                                                placeholder=" Jumlah Stock Barang Baru" required>
                                            <input type="text" id="stokBaruIgd"
                                                class="form-control-sm col border border-primary"
                                                placeholder=" Jumlah Stock Baru IGD" required>
                                            <input type="text" id="stokBaruFarmasi"
                                                class="form-control-sm col border border-primary"
                                                placeholder=" Jumlah Stock Baru Farmasi" required>
                                        </div>
                                        <label for="hargaBeli" class="form-label pt-2"> Harga
                                            Beli* </label>
                                        <div class="col p-0">
                                            <input type="text" id="hargaBeli"
                                                class="form-control-sm col border border-primary"
                                                placeholder="Harga Beli" required>
                                        </div>
                                        <label for="hargaJual" class="form-label pt-2"> Harga
                                            Jual* </label>
                                        <div class="col p-0">
                                            <input type="text" id="hargaJual"
                                                class="form-control-sm col border border-primary"
                                                placeholder="Harga Jual" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="nmObat" class="form-label pt-2">
                                            Nama Obat </label>
                                        <div class="">
                                            <input type="text" id="nmObat"
                                                class="form-control-sm col border border-primary"
                                                placeholder="Nama Obat" readonly>
                                        </div>
                                        <label for="jenis" class="form-label pt-2"> Jenis*
                                        </label>
                                        <div class="">
                                            <select id="jenis"
                                                class="form-control select2bs4 21 border border-primary">
                                                <option value="">--- Pilih Jenis ---</option>
                                                <option value="1">Obat</option>
                                                <option value="2">Bahan Medis Habis Pakai/BMHP
                                                </option>
                                            </select>
                                        </div>
                                        <label for="supplier" class="form-label pt-2">
                                            Supplier* </label>
                                        <div class="">
                                            <select id="supplier"
                                                class="form-control select2bs4 border border-primary" required>
                                                <option value="">--- Pilih supplier ---</option>
                                            </select>
                                            <input id="warningMessage"
                                                class="form-control-sm col border border-primary"
                                                placeholder="warningMessage" hidden readonly>
                                        </div>
                                        <label for="pabrikan" class="form-label pt-2">
                                            Pabrikan Obat* </label>
                                        <div class="">
                                            <select id="pabrikan"
                                                class="form-control select2bs4 border border-primary" required>
                                                <option value="">--- Pabrikan Obat ---</option>
                                            </select>
                                            <input id="sisaStok" class="form-control-sm col border border-primary"
                                                placeholder="sisaStok" readonly hidden>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="sediaan" class="form-label pt-2">
                                            Sediaan*
                                        </label>
                                        <div class="">
                                            <select id="sediaan"
                                                class="form-control select2bs4 border border-primary" required>
                                                <option value="">--Pilih Sediaan--</option>
                                                <option value="AMPUL">AMPUL</option>
                                                <option value="BOTOL">BOTOL</option>
                                                <option value="BOX">BOX</option>
                                                <option value="CAIR">CAIR</option>
                                                <option value="KAPSUL">KAPSUL</option>
                                                <option value="PASANG">PASANG</option>
                                                <option value="PCS">PCS</option>
                                                <option value="RING">RING</option>
                                                <option value="ROL">ROL</option>
                                                <option value="SALEP">SALEP</option>
                                                <option value="SIRUP">SIRUP</option>
                                                <option value="SYRUP">SYRUP</option>
                                                <option value="TABLET">TABLET</option>
                                                <option value="TUBE">TUBE</option>
                                                <option value="VIAL">VIAL</option>
                                            </select>
                                        </div>
                                        <label for="sumberObat" class="form-label pt-2">
                                            Sumber Dana Obat* </label>
                                        <div class="">
                                            <select id="sumberObat"
                                                class="form-control select2bs4 21 border border-primary" required>
                                                <option value="">--- Sumber Dana Obat ---</option>
                                                <option value="UPKF">Gudang Farmasi</option>
                                                <option value="BLUD">BLUD</option>
                                            </select>
                                        </div>
                                        <label for="tglBeli" class="form-label pt-2">
                                            Tanggal Pembelian* </label>
                                        <div class="col p-0">
                                            <input type="date" id="tglBeli"
                                                class="form-control-sm col border border-primary" required>
                                        </div>
                                        <label for="tglED" class="form-label pt-2">
                                            Tanggal Kedaluwarsa* </label>
                                        <div class="col p-0">
                                            <input type="date" id="tglED"
                                                class="form-control-sm col border border-primary" required>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="form-group col-auto" id="farmasi">
                                <span><a id="addStokFarmasi"
                                        class="btn btn-danger d-flex justify-content-center mx-2">+
                                        Tambah Stok Obat Farmasi</a>
                                </span>
                            </div>
                            <div class="form-group col-auto" id="igd">
                                <span><a id="addStokIGD" class="btn btn-warning d-flex justify-content-center mx-2">+
                                        Tambah Stok Obat IGD</a>
                                </span>
                            </div>
                            <div class="form-group col-auto" id="obatNew">
                                <span><a id="addStokGudang"
                                        class="btn btn-success d-flex justify-content-center mx-2">+
                                        Tambah Stok Obat Gudang</a>
                                </span>
                            </div>
                            <div class="form-group col-auto" id="obatNewGudang" hidden>
                                <span><a id="addJenisObat"
                                        class="btn btn-success d-flex justify-content-center mx-2">+
                                        Tambah Jenis Obat</a>
                                </span>
                            </div>
                        </div>
                        <!-- /.card-footer -->
                    </div>
