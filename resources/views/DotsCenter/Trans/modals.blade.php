                <div class="modal fade" id="modal-pasienTB">
                    <div class="modal-dialog modal-dialog modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="card-body p-2">
                                    <div class="container-fluid">
                                        <div class="card card-black">
                                            <!-- form start -->
                                            @csrf
                                            <form class="form-horizontal" id="formTBbaru">
                                                <div class="card-body">
                                                    <div
                                                        class="h5 pb-2 mb-4 text-danger border-bottom border-danger text-center">
                                                        <b>Input Data Dasar Pasien TBC</b>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="modal-norm"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">No
                                                            RM</label>
                                                        <div class="col-sm-2 input-group input-group-sm">
                                                            <input type="text" name="modal-norm" id="modal-norm"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control" placeholder=" No RM" maxlength="6"
                                                                pattern="[0-9]{6}" required />
                                                        </div>

                                                        <label for="modal-layanan"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan</label>
                                                        <div class="col-sm-2 input-group input-group-sm">
                                                            <input type="text" id="modal-layanan"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control bg-white" placeholder="Layanan"
                                                                readonly />
                                                        </div>

                                                        <label for="modal-nama"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">Nama</label>
                                                        <div class="col-sm-5 input-group input-group-sm">
                                                            <input type="text" id="modal-nama"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control bg-white" placeholder="Nama Pasien"
                                                                readonly>
                                                            <input type="text" id="modal-notrans"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control" placeholder="notrans Pasien"
                                                                readonly hidden>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-3">
                                                        <label for="modal-hp"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">No
                                                            HP</label>
                                                        <div class="col-sm-2 input-group input-group-sm">
                                                            <input type="text" id="modal-hp"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control bg-white" placeholder="No HP" />
                                                        </div>

                                                        <label for="modal-nik"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">NIK</label>
                                                        <div class="col-sm-2 input-group input-group-sm">
                                                            <input type="text" id="modal-nik"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control" placeholder="NIK" />
                                                        </div>

                                                        <label for="modal-alamat"
                                                            class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat</label>
                                                        <div class="col-sm-5 input-group input-group-sm">
                                                            <input id="modal-alamat"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control bg-white"
                                                                placeholder="Alamat Pasien" readonly />
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        {{-- <label for="modal-dokter"
                                                            class="col-sm-1 col-form-label font-weight-bold">Dokter</label> --}}
                                                        <div class="col-sm-3">
                                                            <select id="modal-dokter"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control select2bs4 mb-3 border border-primary">
                                                                <option value="">--Pilih Dokter--</option>
                                                                @foreach ($dokter as $item)
                                                                    <option value="{{ $item->nip }}">
                                                                        {{ $item->gelar_d }}
                                                                        {{ $item->nama }} {{ $item->gelar_b }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- <label for="modal-petugas"
                                                            class="col-sm-1 col-form-label font-weight-bold">Petugas</label> --}}
                                                        <div class="col-sm-3">
                                                            <select id="modal-petugas"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="">--Pilih Petugas--</option>
                                                                @foreach (collect($perawat)->sortBy('nama') as $item)
                                                                    <!-- Convert to collection and sort by 'nama' -->
                                                                    <option value="{{ $item->nip }}">
                                                                        {{ $item->gelar_d }} {{ $item->nama }}
                                                                        {{ $item->gelar_b }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- <label for="modal-kdDx"
                                                            class="col-sm-1 col-form-label font-weight-bold">DX
                                                            Medis</label> --}}
                                                        <div class="col-sm-3">
                                                            <select id="modal-kdDx"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control select2bs4 mb-3 border border-primary">
                                                                <option value="">--Pilih Diagnosa--</option>
                                                                @foreach ($dxMed as $item)
                                                                    <option value="{{ $item->kdDiag }}">
                                                                        {{ $item->diagnosa }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- <label for="modal-obtDots"
                                                            class="col-sm-1 col-form-label font-weight-bold">Obat</label> --}}
                                                        <div class="col-sm-3">
                                                            <select id="modal-obtDots"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Jenis Obat--</option>
                                                                @foreach ($obat as $item)
                                                                    <option value="{{ $item->kd }}">
                                                                        {{ $item->nmPengobatan }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        {{-- <label for="modal-bb"
                                                            class="col-sm-1 col-form-label font-weight-bold">BB</label> --}}
                                                        <div class="col-sm-1">
                                                            <input type="text" id="modal-bb"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control form-control-sm border border-info"
                                                                placeholder="BB" required />
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <select name="Modal sample" id="modal-sample"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Sample TCM--</option>
                                                                <option value="Sputum">Sputum</option>
                                                                <option value="Cairan Pleura">Cairan Pleura</option>
                                                                <option value="Jaringan Biopsi">Jaringan Biopsi
                                                                </option>
                                                                <option value="Cairan Lambung">Cairan Lambung</option>
                                                                <option value="Lainnya">Lainnya, Tulis di keterangan
                                                                </option>
                                                            </select>
                                                        </div>
                                                        {{-- <label for="modal-tcm"
                                                            class="col-sm-1 col-form-label font-weight-bold">Hasil
                                                            TCM</label> --}}
                                                        <div class="col-sm-3">
                                                            <select id="modal-tcm"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Pilih Hasil TCM--</option>
                                                                <option value="Tidak Periksa">Tidak Periksa</option>
                                                                <option value="Neg">Negatif</option>
                                                                <option value="Low RifSen">MTB Det Low - RifSen
                                                                </option>
                                                                <option value="Low RifRes">MTB Det Low - RifRes
                                                                </option>
                                                                <option value="Medium RifSen">MTB Det Medium - RifSen
                                                                </option>
                                                                <option value="Medium RifRes">MTB Det Medium - RifRes
                                                                </option>
                                                                <option value="Hight RifSen">MTB Det Hight - RifSen
                                                                </option>
                                                                <option value="Hight RifRes">MTB Det Hight - RifRes
                                                                </option>
                                                                <option value="Trace RifSen">MTB Trace Det - RifSen
                                                                </option>
                                                                <option value="Trace Neg">MTB Trace Negatif
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <select id="modal-hiv"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Pilih Status HIV--</option>
                                                                <option value="Positif HIV">Positif</option>
                                                                <option value="Negatif HIV">Negatif</option>
                                                                <option value="Tidak Diketahui">Tidak Diketahui
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <select id="modal-dm"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Pilih Status DM--</option>
                                                                <option value="Positif DM">Positif</option>
                                                                <option value="Negatif DM">Negatif</option>
                                                                <option value="Tidak Diketahui">Tidak Diketahui
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-3">
                                                        <label for="modal-tglmulai"
                                                            class="col-sm-1 col-form-label font-weight-bold">Tgl
                                                            Mulai</label>
                                                        <div class="col-sm-3 input-group input-group-sm">
                                                            <input id="modal-tglmulai" type="date"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control border border-primary" />
                                                        </div>
                                                        <div class="col-sm input-group input-group-sm">
                                                            <input type="text" id="modal-ket"
                                                                aria-describedby="inputGroup-sizing-sm"
                                                                class="form-control border border-info"
                                                                placeholder="Keterangan Lain" />
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="h5 pb-2 mb-4 text-black border-bottom border-danger text-center">
                                                        <b>Input Data Transaksi / Kunjungan Hari ini</b>
                                                    </div>
                                                    <div class="form-group row mt-3">
                                                        <label for="modal-bta"
                                                            class="col-sm-1 col-form-label font-weight-bold"> Hasil
                                                            BTA</label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-bta"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="">--Pilih Hasil BTA--</option>
                                                                <option value="negatif">Negatif</option>
                                                                <option value="+1">Positif 1</option>
                                                                <option value="+2">Positif 2</option>
                                                                <option value="+3">Positif 3</option>
                                                                <option value="+1-9">Positif 1-9</option>
                                                            </select>
                                                        </div>
                                                        <label for="modal-blnKe"
                                                            class="col-sm-1 col-form-label font-weight-bold">
                                                            Bulan Ke
                                                        </label>
                                                        <div class="col-sm-3">
                                                            <select id="modal-blnKe"
                                                                onchange="setKontrol('modal-blnKe','modal-nxKontrol');"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="">--Pilih Kemajuan--</option>
                                                                @foreach ($bulan as $item)
                                                                    <option nilai="{{ $item->nilai }}"
                                                                        value="{{ $item->id }}">
                                                                        {{ $item->nmBlnKe }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <label for="modal-nxKontrol"
                                                            class="col-sm-1 col-form-label font-weight-bold"> Kontrol
                                                            :</label>
                                                        <div class="col-sm-3">
                                                            <input id="modal-nxKontrol" type="date"
                                                                class="form-control-sm col border border-primary" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-3">
                                                        <div class="col-sm-12">
                                                            <a id="addPTB"
                                                                class="btn btn-success d-flex justify-content-center"
                                                                onclick="validasiDaftar();">Simpan
                                                                Data Pasien</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>

                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Data Pasien TBC Baru Hari Ini</h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <!-- form start -->
                                            <div class="card-body p-2">
                                                <div class="table-responsive">
                                                    <table id="modal-Ptb"
                                                        class="table table-striped table-hover pt-0 mt-0 fs-6"
                                                        style="width:100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th width="15px">Aksi</th>
                                                                <th width="35px">Mulai</th>
                                                                <th width="15px"class="text-center">No</th>
                                                                <th width="15px" class="text-center">NoRM</th>
                                                                <th width="15px"class="text-center">No HP</th>
                                                                <th width="36px"class="text-center">Status</th>
                                                                <th width="">Nama</th>
                                                                <th width="">Alamat</th>
                                                                <th width="">Dokter</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <div class="modal fade" id="modal-RiwayatKunjungan">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Riwayat Kunjungan Pasien TBC</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body p-2">
                                    <div class="container-fluid">
                                        <div class="card card-black">
                                            <!-- form start -->
                                            <div class="form-horizontal" id="identitasTBRiwayat">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for="riwayat-norm"
                                                            class="col-sm-1  font-weight-bold mb-0">No
                                                            RM</label>
                                                        <span id="riwayat-norm" class="col-sm-1">: NaN</span>

                                                        <label for="riwayat-nama"
                                                            class="col-sm-1  font-weight-bold mb-0">Nama</label>
                                                        <span id="riwayat-nama" class="col-sm">: NaN</span>

                                                        <label for="riwayat-hp "
                                                            class="col-sm-1  font-weight-bold mb-0">No
                                                            HP</label>
                                                        <span id="riwayat-hp" class="col-sm-2">: NaN</span>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <label for="none"
                                                            class="col-sm-1  font-weight-bold mb-0"></label>
                                                        <span id="none" class="col-sm-1"></span>
                                                        <label for="riwayat-alamat"
                                                            class="col-sm-1  font-weight-bold mb-0">Alamat</label>
                                                        <span id="riwayat-alamat" class="col-sm">: NaN</span>

                                                        <label for="riwayat-nik"
                                                            class="col-sm-1  font-weight-bold mb-0">NIK</label>
                                                        <span id="riwayat-nik" class="col-sm-2">: NaN</span>

                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                        </div>

                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Data Riwayat Kunjungan Pasien TBC</h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <!-- form start -->
                                            <div class="card-body p-2">
                                                <div class="table-responsive">
                                                    <table id="modal-kunjDots" name="Riwayat Kunjungan Pasien TBC"
                                                        class="table table-striped" style="width:100%"
                                                        cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-1">Aksi</th>
                                                                <th>No RM</th>
                                                                <th>Tgl Kontrol</th>
                                                                <th>Bln Ke</th>
                                                                <th>BTA</th>
                                                                <th>BB</th>
                                                                <th>Terapi</th>
                                                                <th class="col-3">Petugas</th>
                                                                <th class="col-3">Dokter</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <div class="modal fade" id="modal-update">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Update Status Pengobatan Pasien TBC</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body p-2">
                                    <div class="container-fluid">
                                        <div class="card card-black">
                                            <!-- form start -->
                                            @csrf
                                            <form class="form-horizontal" id="updatePengobatanTB">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-sm-1 row">
                                                            <input type="number" name="status-norm" id="status-norm"
                                                                class="form-control col p-0 text-center"
                                                                placeholder=" No RM" maxlength="6"
                                                                pattern="[0-9]{6}" readonly />
                                                            <input type="number" name="status-id" id="status-id"
                                                                class="form-control col" placeholder="ID"
                                                                maxlength="6" pattern="[0-9]{6}" readonly
                                                                style="display: none;" />
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" id="status-nama"
                                                                class="form-control border border-white"
                                                                placeholder="Nama Pasien" readonly>
                                                        </div>
                                                        <div class="col-sm">
                                                            <input id="status-alamat"
                                                                class="form-control border border-white"
                                                                placeholder="Alamat Pasien" readonly />
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-sm">
                                                            <label for="statusPengobatan"
                                                                class="col-form-label font-weight-bold">Hasil
                                                                Berobat</label>
                                                            <select id="statusPengobatan"
                                                                class="form-control select2bs4 border border-primary">
                                                                <option value="">--Pilih Kemajuan--</option>
                                                                @foreach ($bulan as $item)
                                                                    <option nilai="{{ $item->nilai }}"
                                                                        value="{{ $item->id }}">
                                                                        {{ $item->nmBlnKe }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm">
                                                            <label for="modal-tcm-update"
                                                                class="col-form-label font-weight-bold">Hasil
                                                                TCM</label>
                                                            <select id="modal-tcm-update"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Pilih Hasil--</option>
                                                                <option value="Tidak Periksa">Tidak Periksa</option>
                                                                <option value="Neg">Negatif</option>
                                                                <option value="Low RifSen">MTB Det Low - RifSen
                                                                </option>
                                                                <option value="Low RifRes">MTB Det Low - RifRes
                                                                </option>
                                                                <option value="Medium RifSen">MTB Det Medium - RifSen
                                                                </option>
                                                                <option value="Medium RifRes">MTB Det Medium - RifRes
                                                                </option>
                                                                <option value="Hight RifSen">MTB Det Hight - RifSen
                                                                </option>
                                                                <option value="Hight RifRes">MTB Det Hight - RifRes
                                                                </option>
                                                                <option value="Trace RifSen">MTB Trace Det - RifSen
                                                                </option>
                                                                <option value="Trace Neg">MTB Trace Negatif
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm">
                                                            <label for="modal-sample-update"
                                                                class="col-form-label font-weight-bold">Sample
                                                                TCM</label>
                                                            {{-- <input type="text" id="modal-sample"
                                                                    class="form-control form-control-sm border border-info"
                                                                    placeholder="Sample" required /> --}}
                                                            <select name="Modal sample" id="modal-sample-update"
                                                                class="form-control select2bs4 border border-info">
                                                                <option value="">--Sample TCM--</option>
                                                                <option value="Sputum">Sputum</option>
                                                                <option value="Cairan Pleura">Cairan Pleura</option>
                                                                <option value="Jaringan Biopsi">Jaringan Biopsi
                                                                </option>
                                                                <option value="Cairan Lambung">Cairan Lambung</option>
                                                                <option value="Lainnya">Lainnya, Tulis di keterangan
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col">
                                                            <label for="modal-ket-update"
                                                                class="col-form-label font-weight-bold">Keterangan Lain</label>
                                                            <input type="text" id="modal-ket-update"
                                                                class="form-control form-control-sm border border-info"
                                                                placeholder="Keterangan Lainnya" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    onclick="updateStatus();">Simpan</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                </body>

                </html>
