                <div class="container-fluid">
                    <div class="card card-lime">
                        <div class="card-header">
                            <h4 class="card-title">Identitas</h4>
                        </div>
                        @csrf
                        <form class="form-horizontal">
                            <div class="card-body" id="inputSection">
                                <div class="form-grup row">
                                    <label for="norm" class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                        :</label>
                                    <div class="col-sm-2 input-group" style="overflow: hidden;">
                                        <input type="number" name="norm" id="norm" class="form-control"
                                            placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required
                                            onkeyup="if (event.key === 'Enter') {var tgl = $('#tgltrans').val(); var norm = $('#norm').val(); cariKominfo(norm, tgl);}" />
                                        <div class="input-group-addon btn btn-danger">
                                            <span class="fa-solid fa-magnifying-glass" onclick="searchRMObat();"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Selain Pasien Hari ini"></span>
                                        </div>
                                        <marquee id="ket" class="col-sm-12 text-danger font-weight-bold"
                                            direction="left" behavior="scroll" scrollamount="5">
                                            Klik Tombol Cari berwarna merah untuk menginputkan obat non pasien
                                        </marquee>
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
                                            placeholder="tgltrans" />
                                    </div>
                                    <label for="notrans" class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="notrans" class="form-control bg-white"
                                            placeholder="Nomor Transaksi" readonly />
                                    </div>
                                    <label for="alamat" class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                        :</label>
                                    <div class="col-sm-5">
                                        <input id="alamat" class="form-control bg-white" placeholder="Alamat Pasien"
                                            readonly />
                                    </div>
                                </div>
                                <div class="mt-2 form-grup row d-flex justify-content-center">
                                    <label for="apoteker" class="col-sm-1 col-form-label font-weight-bold">Petugas
                                        :</label>
                                    <div class="col-sm-4">
                                        <select id="apoteker" class="form-control select2bs4 border border-primary">
                                            <option value="">--Pilih Petugas--</option>
                                        </select>
                                    </div>
                                    <label for="dokter" class="col-sm-1 col-form-label font-weight-bold">Dokter
                                        :</label>
                                    <div class="col-sm-4">
                                        <select id="dokter"
                                            class="form-control select2bs4 mb-3 border border-primary">
                                            <option value="">--Pilih Dokter--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3 form-grup d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary col" data-toggle="modal"
                                        data-target="#riwayatModal" onclick="showRiwayat()">Lihat
                                        Riwayat
                                        Transaksi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h4 class="card-title">Input Obat</h4>
                        </div>
                        @csrf
                        <form class="card-body form-horizontal px-1" id="form-obat">
                            <div class="container-fluid d-flex justify-content-center p-2">
                                <div class="col-sm-6 ">
                                    <select id="obat" class="form-control select2bs4 border border-primary">
                                        <option value="">--Pilih obat--</option>
                                    </select>
                                </div>
                                <div class="col-sm-1 ">
                                    <input type="text"id="productID" class="form-control  border border-info"
                                        placeholder="ID Produk" readonly>
                                </div>
                                <div class="col-sm-1 ">
                                    {{-- saat tekan enter panggil onclick="simpanFarmasi();" --}}
                                    <input type="number"id="qty" class="form-control  border border-info"
                                        placeholder="Jumlah" oninput="hitungTotalHarga(this.value)"
                                        onkeyup="if (event.key === 'Enter') {simpanFarmasi();}">
                                </div>
                                {{-- <div class="col-sm-1 ">
                                    <label for="qty" class="col-form-label"><b>Jumlah :</b></label>
                                    <select id="qty" class="form-control border border-info"
                                        onchange="checkSelectedOption(this)">
                                        <option value="" selected disabled>Jumlah</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="10">10</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                    <div id="otherQty" style="display: none;">
                                        <label for="otherQtyInput" class="col-form-label"><b>Masukkan Jumlah
                                                :</b></label>
                                        <input type="text" id="otherQtyInput"
                                            class="form-control border border-info" placeholder="Masukkan Jumlah">
                                    </div>
                                </div> --}}
                                <div class="col-sm-1 ">
                                    <input type="text"id="jual" class="form-control  border border-info"
                                        placeholder="Jual" onkeyup="if (event.key === 'Enter') {simpanFarmasi();}">
                                </div>
                                <div class="col-sm-1 " hidden>
                                    <input type="text"id="beli" class="form-control  border border-info"
                                        placeholder="Beli" readonly>
                                </div>
                                <div class="col-sm-1 ">
                                    <input type="text"id="total" class="form-control  border border-info"
                                        placeholder="Total" readonly>
                                </div>
                                <button id="addFarmasi" class="btn btn-success" onclick="simpanFarmasi();">+
                                    Obat</button>
                            </div>
                            <div class="container-fluid row mx-0">
                                <div class="card card-warning col p-0 mr-1">
                                    <div class="card-header">
                                        <h4 class="card-title">Data
                                            Transaksi Obat Farmasi</h4>
                                    </div>
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
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="loadingSpinner" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                                <div class="card card-lime col p-0 ml-1">
                                    <div class="card-header">
                                        <h4 class="card-title">Data
                                            Transaksi Obat & BMHP IGD</h4>
                                    </div>
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
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="loadingSpinner" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="card-footer form-row d-flex justify-content-end">
                            <div class="col-md-2 d-flex justify-content-end d-flex align-items-center">
                                <label for="tagihan" class="form-label mb-0"><b>Total
                                        Tagihan :</b></label>
                            </div>
                            <div class="col-md-2">
                                <input type="text"id="tagihan" class="form-control border border-info"
                                    placeholder="Total Tagihan" readonly>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-danger" id="tblBatal" onclick="rstForm();">Batal</a>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-success" id="tblSimpan">Selesai</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{--
                </div> --}}
