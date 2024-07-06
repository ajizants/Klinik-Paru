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
                                        <div class="input-group-addon btn btn-danger">
                                            <span class="fa-solid fa-magnifying-glass" onclick="searchRMObat();"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Selain Pasien Hari ini"></span>
                                        </div>
                                        <marquee id="ket" class="col-sm-12 text-danger font-weight-bold"
                                            direction="left" behavior="scroll" scrollamount="5">
                                            Klik Tombol Cari berwarna merah untuk menginputkan pemeriksaan bukan pasien
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
                                    <label for="nik" class="col-sm-1 col-form-label font-weight-bold mb-0">NIK
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="nik" class="form-control bg-white"
                                            placeholder="nik" />
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

                                <div class="mt-3 form-grup d-flex justify-content-center" hidden>
                                    <button type="button" class="btn btn-primary col" data-toggle="modal"
                                        data-target="#riwayatModal" onclick="showRiwayat()" hidden>Lihat
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
                            <h4 class="card-title">Input Pemeriksaan Laboratorium</h4>
                        </div>
                        @csrf
                        <div class="card-body p-2">
                            <div class="row px-2">
                                <div class="LayLab col mr-2">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">Pilih Pemeriksaan Bakteriologi</h4>
                                        </div>
                                        <div class="card-body py-1 card-body table-responsive">
                                            <table id="bakteriologi" class="table">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="pilih-bakteriologi"></th>
                                                        <th>Item Pemeriksaan</th>
                                                        {{-- <th>Hasil</th> --}}
                                                        <th>Harga</th>
                                                        {{-- <th><input type="checkbox" id="pilih-tag-bakteriologi"></th>
                                                        <th>Tagihan</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">Pilih Pemeriksaan Hematologi</h4>
                                        </div>
                                        <div class="card-body py-1 card-body table-responsive">
                                            <table id="hematologi" class="table">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="pilih-hematologi"></th>
                                                        <th>Item Pemeriksaan</th>
                                                        {{-- <th>Hasil</th> --}}
                                                        <th>Harga</th>
                                                        {{-- <th><input type="checkbox" id="pilih-tag-hematologi"></th>
                                                        <th>Tagihan</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="LayLab col">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">Pilih Pemeriksaan Kimia Darah</h4>
                                        </div>
                                        <div class="card-body py-1 card-body table-responsive">
                                            <table id="kimia" class="table">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="pilih-kimia"></th>
                                                        <th>Item Pemeriksaan</th>
                                                        {{-- <th>Hasil</th> --}}
                                                        <th>Harga</th>
                                                        {{-- <th><input type="checkbox" id="pilih-tag-kimia"></th>
                                                            <th>Tagihan</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">Pilih Pemeriksaan Imuno Serologi</h4>
                                        </div>
                                        <div class="card-body py-1 card-body table-responsive">
                                            <table id="imuno" class="table">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="pilih-imuno"></th>
                                                        <th>Item Pemeriksaan</th>
                                                        {{-- <th>Hasil</th> --}}
                                                        <th>Harga</th>
                                                        {{-- <th><input type="checkbox" id="pilih-tag-imuno"></th>
                                                            <th>Tagihan</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row px-2">
                                <div class="col p-0">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h4 class="card-title">Pemeriksaan yang dilakukan</h4>
                                        </div>
                                        <div class="card-body py-1">
                                            <table id="dataTrans" class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="no-total" width="35px">Aksi</th>
                                                        <th>NO</th>
                                                        <th>NO RM</th>
                                                        <th>Item Pemeriksaan</th>
                                                        {{-- <th>Hasil</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="form_Petugas">
                                <div class="mx-2 py-2 form-grup row">
                                    <div class="col">
                                        <textarea type="text"id="keterangan" class="form-control-sm col border border-info"
                                            placeholder="Keterangan Tambahan" readonly style="height: 50px;"></textarea>
                                    </div>
                                </div>
                                <div class="mx-2 form-grup row">
                                    <label for="dokter" class="col-sm-1 col-form-label font-weight-bold">Dokter
                                        :</label>
                                    <div class="col-sm-3">
                                        <select id="dokter"
                                            class="form-control select2bs4 mb-3 border border-primary" required>
                                            <option value="">--Pilih Dokter--</option>
                                        </select>
                                    </div>
                                    <label for="analis" class="col-sm-1 col-form-label font-weight-bold">Admin
                                        :</label>
                                    <div class="col-sm-3">
                                        <select id="analis" class="form-control select2bs4 border border-primary"
                                            required>
                                            <option value="">--Pilih Petugas--</option>
                                        </select>
                                    </div>
                                    <label for="tujuan" class="col-sm-1 col-form-label font-weight-bold"
                                        hidden>Tujuan
                                        :</label>
                                    <div class="col-sm-2">
                                        <select id="tujuan" class="form-control select2bs4 border border-primary"
                                            required hidden>
                                            <option value="">--Pilih Tujuan--</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <div class="card-footer form-row d-flex justify-content-end  d-flex align-items-center">
                                {{-- <div class="col-md-2 d-flex justify-content-end">
                                    <label for="tagihan" class="form-label mb-0"><b>Total
                                            Tagihan :</b></label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text"id="tagihan" class="form-control-sm border border-info"
                                        placeholder="Total Tagihan" readonly>
                                </div> --}}

                                <div class="col-auto">
                                    <a class="btn btn-success" id="tblSimpan" onclick="simpan();">Simpan</a>
                                </div>
                                <div class="col-auto">
                                    <a class="btn btn-danger" id="tblBatal"
                                        onclick="resetForm('dibatalkan');">Batal</a>
                                </div>

                                <div class="col-auto">
                                    <a class="btn btn-primary" id="tblSelesai"
                                        onclick="resetForm('selesai');">Selesai</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
