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
                                        <input type="text" name="norm" id="norm" class="form-control"
                                            placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required />
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
                                    <label for="tgltind" class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="tgltind" class="form-control bg-white"
                                            placeholder="Tanggal" readonly hidden />
                                        <input type="text" id="tgltrans" class="form-control bg-white"
                                            placeholder="tgltrans" readonly />
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
                                    <label for="analis" class="col-sm-1 col-form-label font-weight-bold">Petugas
                                        :</label>
                                    <div class="col-sm-4">
                                        <select id="analis" class="form-control select2bs4 border border-primary">
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
                            <h4 class="card-title">Input Pemeriksaan Laboratorium</h4>
                        </div>
                        @csrf
                        <div class="card-body form-horizontal px-1">
                            <div class="container-fluid d-flex justify-content-center p-2">
                            </div>
                            <div class="container-fluid row mx-0">
                                <div class=" col p-0 ml-1">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">Hematologi Darah</h4>
                                        </div>
                                        <div class="card-body py-1">
                                            <table id="tabelPemeriksaan" class="table">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="select-all"></th>
                                                        <th>Item Pemeriksaan</th>
                                                        <th>Hasil</th>
                                                        <th>Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- <div class="card card-info">
                                        <div class="card-header">
                                            <h4 class="card-title">Imuno Serologi</h4>
                                        </div>
                                        <div class="card-body py-1">
                                            <form id="frmImuno">
                                                <div class="row my-3 form-inline">
                                                    <div class="form-check form-switch col">
                                                        <a class="col-auto btn btn-success"
                                                            onclick="checkAll('frmImuno')">Pemeriksaan</a>
                                                    </div>
                                                    <div class="col">
                                                        <p class="form-control-sm m-0" type="text">HARGA</p>
                                                    </div>
                                                    <div class="col">
                                                        <p class="form-control-sm m-0" type="text">HASIL</p>
                                                    </div>
                                                </div>
                                                <div class="row my-3">
                                                    <div class="form-check form-switch col form-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            role="switch" id="HIV">
                                                        <label class="form-check-label" for="HIV">HIV</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control-sm col" type="text"
                                                            id="hargaHIV" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control-sm col" type="text"
                                                            id="hasilHIV">
                                                    </div>
                                                </div>
                                                <div class="row my-3">
                                                    <div class="form-check form-switch col form-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            role="switch" id="RDT">
                                                        <label class="form-check-label" for="RDT">RDT</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control-sm col" type="text"
                                                            id="hargaRDT" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control-sm col" type="text"
                                                            id="hasilRDT">
                                                    </div>
                                                </div>
                                                <div class="row my-3">
                                                    <div class="form-check form-switch col form-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            role="switch" id="Syphilis">
                                                        <label class="form-check-label"
                                                            for="Syphilis">Syphilis</label>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control-sm col" type="text"
                                                            id="hargaSyphilis" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control-sm col" type="text"
                                                            id="hasilSyphilis">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div> --}}
                                    {{-- </div>
                                <div class="card card-orange col p-0 ml-1">
                                    <div class="card-header">
                                        <h4 class="card-title">Kimia Darah</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <form id="frmKimia">
                                            <div class="row my-3 form-inline">
                                                <div class="form-check form-switch col">
                                                    <a class="col-auto btn btn-success"
                                                        onclick="checkAll('frmKimia')">Pemeriksaan</a>
                                                </div>
                                                <div class="col">
                                                    <p class="form-control-sm m-0" type="text">HARGA</p>
                                                </div>
                                                <div class="col">
                                                    <p class="form-control-sm m-0" type="text">HASIL</p>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="AU">
                                                    <label class="form-check-label" for="AU">Asam Urat</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaAU"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilAU">
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="CH">
                                                    <label class="form-check-label" for="CH">Cholesterol</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaCH"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilCH">
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="GDS">
                                                    <label class="form-check-label" for="GDS">Glukosa
                                                        Darah</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaGDS"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilGDS">
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="SGOT">
                                                    <label class="form-check-label" for="SGOT">SGOT</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaSGOT"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilSGOT">
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="SGPT">
                                                    <label class="form-check-label" for="SGPT">SGPT</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaSGPT"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilSGPT">
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="UR">
                                                    <label class="form-check-label" for="UR">Ureum</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaUR"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilUR">
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="CRE">
                                                    <label class="form-check-label" for="CRE">Creatinine</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaCRE"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilCRE">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card card-lime col p-0 ml-1">
                                    <div class="card-header">
                                        <h4 class="card-title">Pemeriksaan Dahak</h4>
                                    </div>
                                    <div class="card-body py-1">
                                        <form id="frmDahak">
                                            <div class="row my-3 form-inline">
                                                <div class="form-check form-switch col">
                                                    <a class="col-auto btn btn-success"
                                                        onclick="checkAll('frmDahak')">Pemeriksaan</a>
                                                </div>
                                                <div class="col">
                                                    <p class="form-control-sm m-0" type="text">HARGA</p>
                                                </div>
                                                <div class="col">
                                                    <p class="form-control-sm m-0" type="text">HASIL</p>
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="TCM">
                                                    <label class="form-check-label" for="TCM">TCM</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaTCM"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilTCM">
                                                </div>
                                            </div>
                                            <div class="row my-3">
                                                <div class="form-check form-switch col form-inline">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="BTA">
                                                    <label class="form-check-label" for="BTA">BTA</label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hargaBTA"
                                                        readonly>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control-sm col" type="text" id="hasilBTA">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div> --}}
                                </div>
                            </div>
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
                                    <a class="btn btn-danger" id="tblBatal">Batal</a>
                                </div>
                                <div class="col-auto">
                                    <a class="btn btn-success" id="tblSimpan">Selesai</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
