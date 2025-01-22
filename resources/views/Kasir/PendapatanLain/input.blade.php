                {{-- input tindakan --}}
                <div class="card shadow mb-4">
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2">
                            <div class="container-fluid">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a type="button" class="nav-link border border-primary  active bg-blue"
                                            onclick=" toggleSections('#dSelesai')"><b>Penutupan Kas</b></a>
                                    </li>
                                    <li class="nav-item">
                                        <a type="button" class="nav-link border border-primary"
                                            onclick=" toggleSections('#dTunggu')"><b>Transaksi Lainnya</b></a>
                                    </li>
                                </ul>
                                <div class="border border-primary p-2" id="dTunggu" style="display: none">
                                    @csrf
                                    <form class="form-group " id="form_input">
                                        <div class="form-row">
                                            <div class="form-group col mx-2">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-2" for="tanggal">Tgl Setor
                                                        :</label>
                                                    <div class="col">
                                                        <input type="date" id="tanggal" name="tanggal"
                                                            class="form-control bg-white" placeholder="Tanggal"
                                                            value="{{ date('Y-m-d') }}" />
                                                    </div>
                                                    <div class="col-3">
                                                        <a type="button" class="btn btn-success py-2"
                                                            onclick="cariPendapatan();">Cari Pendapatan</a>
                                                    </div>
                                                    <script>
                                                        function cariPendapatan() {
                                                            var tanggal = document.getElementById("tanggal").value;
                                                            //cari data get api/pendapatanTgl+tanggal
                                                            $.ajax({
                                                                url: '/api/pendapatanTgl/' + tanggal,
                                                                type: 'GET',
                                                                dataType: 'JSON',
                                                                success: function(response) {
                                                                    console.log(response);
                                                                    if (response == false) {
                                                                        tampilkanEror("Data tidak ditemukan...!");
                                                                    } else {
                                                                        tampilkanSuccess("Data ditemukan...!");
                                                                        const pendapatan = formatRupiah(response.jumlah)
                                                                        document.getElementById("pendapatan").value = pendapatan;
                                                                        document.getElementById("noSbs").value = response.nomor;
                                                                    }
                                                                },
                                                                error: function(xhr, status, error) {
                                                                    console.log(error);
                                                                    tampilkanEror('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.' + error);
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                    <div class="col-2">
                                                        <input type="number" id="id"
                                                            class="form-control bg-white" placeholder="ID" readonly />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-2" for="masuk">Masuk :</label>
                                                    <div class="col-sm">
                                                        <input type="text" id="pendapatan"
                                                            class="form-control bg-white"
                                                            placeholder="Masuk / Pendapatan / Saldo Bank / Tunai" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-2" for="keluar">Keluar :</label>
                                                    <div class="col-sm">
                                                        <input type="text" id="setoran"
                                                            class="form-control bg-white"
                                                            placeholder="Keluar / Setoran" />
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <a type="button" class="btn btn-warning py-2"
                                                            onclick="copy();">= Masuk</a>
                                                    </div>
                                                    <script>
                                                        function copy() {
                                                            var masuk = document.getElementById("pendapatan").value;
                                                            document.getElementById("setoran").value = masuk;
                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                            <div class="form-group col mx-2">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-2" for="penyetor">Petugas :</label>
                                                    <div class="col-sm">
                                                        <select id="penyetor" class="form-control select2">
                                                            <option value="Nasirin">Nasirin</option>
                                                            <option value="Desiana Budi P.">Desiana Budi P.
                                                            </option>
                                                            <option value="Popy P.">Popy P.</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-2"
                                                        for="asal_pendapatan">Keterangan:</label>
                                                    <div class="col-sm">
                                                        <select name="asal_pendapatan" id="asal_pendapatan"
                                                            class="form-control">
                                                            <option value="3.003.25581.5">Rawat Jalan</option>
                                                            <option value="Klaim BPJS">Klaim BPJS</option>
                                                            <option value="TCM">TCM</option>
                                                            <option value="Bunga">Bunga Bank</option>
                                                            <option value="Tunai">Tunai</option>
                                                            <option value="Saldo Bank">Saldo Bank</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col d-flex justify-content-end">
                                                        <label class="col-form-label col-2" for="keluar">No SBS
                                                            :</label>
                                                        <div class="col-sm">
                                                            <input type="text" id="noSbs"
                                                                class="form-control bg-white" placeholder="No SBS"
                                                                readonly />
                                                        </div>
                                                        <a type="button" id="btnSimpan" class="mx-2 btn  btn-primary"
                                                            onclick="simpanPendLain();">Simpan</a>
                                                        <a type="button" id="btnBatal" class="mx-2 btn  btn-secondary"
                                                            onclick="resetForm('lainnya');">Batal</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="container-fluid" id="formLayanan">
                                        <div class="p-0 ml-2 card card-warning">
                                            <div class="card-header">
                                                <h3 class="card-title">Data
                                                    Transaksi Pendapatan/Pengeluaran Lain</h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body p-4">
                                                <div class="form-group row">
                                                    <label for="tahun" class="col-form-label col-1">Tahun :</label>
                                                    <div class="col-2">
                                                        <select name="Tahun" id="tahun" class="form-control">
                                                            <option value="all">Semua
                                                            </option>
                                                            @foreach ($listYear as $item)
                                                                <option value="{{ $item }}">{{ $item }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button class="btn btn-success" onclick="getDataPendLain()">Cari
                                                        Data</button>
                                                </div>
                                                <div class="table-responsive">
                                                    <table id="dataPendapatanLain" name="dataPendapatanLain"
                                                        class="table table-striped table-tight table-hover"
                                                        style="width:100%" cellspacing="0">
                                                        <thead class="bg-secondary">
                                                            <tr>
                                                                <th>Aksi</th>
                                                                <th>No</th>
                                                                <th>Tanggal</th>
                                                                <th>Masuk</th>
                                                                <th>Keluar</th>
                                                                <th>Keterangan</th>
                                                                <th>Petugas</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border border-primary p-2" id="dSelesai">
                                    @csrf
                                    <form class="form-group" id="form_input_tutup_kas">
                                        <div class="form-row">
                                            <!-- Tanggal Penutupan Sekarang -->
                                            <div class="form-group col-1 mx-2">
                                                <label for="tanggal_sekarang" class="col-form-label">ID:</label>
                                                <div class="form-row">
                                                    <input type="number" id="idTutup" name="idTutup"
                                                        class="form-control bg-white" />
                                                </div>
                                            </div>
                                            <!-- Tanggal Penutupan Sekarang -->
                                            <div class="form-group col mx-2">
                                                <label for="tanggal_sekarang" class="col-form-label">Tanggal penutupan
                                                    KAS sekarang:</label>
                                                <div class="form-row">
                                                    <input type="date" id="tanggal_sekarang"
                                                        name="tanggal_sekarang" class="form-control col-8 bg-white"
                                                        value="{{ date('Y-m-d') }}" />
                                                    <a class="btn btn-warning mx-3 col-3"
                                                        onclick="getDataPenutupanKas()">Cari
                                                        Data</a>
                                                </div>
                                            </div>
                                            <!-- Tanggal Penutupan Lalu -->
                                            <div class="form-group col mx-2">
                                                <label for="tanggal_lalu" class="col-form-label">Tanggal penutupan KAS
                                                    yang lalu:</label>
                                                <input type="date" id="tanggal_lalu" name="tanggal_lalu"
                                                    class="form-control bg-white" value="{{ date('Y-m-d') }}" />
                                            </div>
                                            <!-- Petugas -->
                                            <div class="form-group col-2 mx-2">
                                                <label for="petugas" class="col-form-label">Petugas:</label>
                                                <select id="petugas" name="petugas" class="form-control select2">
                                                    <option value="Nasirin">Nasirin</option>
                                                    <option value="Desiana Budi P.">Desiana Budi P.</option>
                                                    <option value="Popy P.">Popy P.</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <!-- Jumlah Total Penerimaan -->
                                            <div class="form-group col mx-2">
                                                <label for="total_penerimaan" class="col-form-label">Jumlah Total
                                                    Penerimaan:</label>
                                                <input type="text" id="total_penerimaan" name="total_penerimaan"
                                                    class="form-control bg-white"
                                                    placeholder="Jumlah Total Penerimaan" />
                                            </div>
                                            <!-- Jumlah Total Pengeluaran -->
                                            <div class="form-group col mx-2">
                                                <label for="total_pengeluaran" class="col-form-label">Jumlah Total
                                                    Pengeluaran:</label>
                                                <input type="text" id="total_pengeluaran" name="total_pengeluaran"
                                                    class="form-control bg-white"
                                                    placeholder="Jumlah Total Pengeluaran" />
                                            </div>
                                            <!-- Saldo Buku Kas Umum -->
                                            <div class="form-group col mx-2">
                                                <label for="saldo_bku" class="col-form-label">Saldo buku KAS
                                                    umum:</label>
                                                <input type="text" id="saldo_bku" name="saldo_bku"
                                                    class="form-control bg-white" placeholder="Saldo buku KAS umum" />
                                            </div>
                                            <!-- Saldo Kas -->
                                            <div class="form-group col mx-2">
                                                <label for="saldo_kas" class="col-form-label">Saldo KAS:</label>
                                                <input type="text" id="saldo_kas" name="saldo_kas"
                                                    class="form-control bg-white" placeholder="Saldo KAS" />
                                            </div>
                                            <!-- Selisih Saldo -->
                                            <div class="form-group col mx-2">
                                                <label for="selisih_saldo" class="col-form-label">Selisih Saldo KAS
                                                    dan BKU:</label>
                                                <input type="text" id="selisih_saldo" name="selisih_saldo"
                                                    class="form-control bg-white" placeholder="Selisih Saldo" />
                                            </div>
                                        </div>
                                        <!-- Denominasi -->
                                        <div class="form-row mx-1">
                                            <!-- Baris 1 -->
                                            <div class="form-group col-md-2">
                                                {{-- <label for="kertas100k">Kertas Rp. 100.000</label> --}}
                                                <input type="number" class="form-control" id="kertas100k"
                                                    name="kertas100k" placeholder="Kertas Rp. 100.000"
                                                    min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="kertas50k">Kertas Rp. 50.000</label> --}}
                                                <input type="number" class="form-control" id="kertas50k"
                                                    name="kertas50k" placeholder="Kertas Rp. 50.000" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="kertas20k">Kertas Rp. 20.000</label> --}}
                                                <input type="number" class="form-control" id="kertas20k"
                                                    name="kertas20k" placeholder="Kertas Rp. 20.000" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="kertas10k">Kertas Rp. 10.000</label> --}}
                                                <input type="number" class="form-control" id="kertas10k"
                                                    name="kertas10k" placeholder="Kertas Rp. 10.000" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="kertas5k">Kertas Rp. 5.000</label> --}}
                                                <input type="number" class="form-control" id="kertas5k"
                                                    name="kertas5k" placeholder="Kertas Rp. 5.000" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="kertas2k">Kertas Rp. 2.000</label> --}}
                                                <input type="number" class="form-control" id="kertas2k"
                                                    name="kertas2k" placeholder="Kertas Rp. 2.000" min="0">
                                            </div>
                                        </div>
                                        <div class="form-row mx-1">
                                            <!-- Baris 2 -->
                                            <div class="form-group col-md-2">
                                                {{-- <label for="kertas1k">Kertas Rp. 1.000</label> --}}
                                                <input type="number" class="form-control" id="kertas1k"
                                                    name="kertas1k" placeholder="Kertas Rp. 1.000" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="logam1k">Logam Rp. 1.000</label> --}}
                                                <input type="number" class="form-control" id="logam1k"
                                                    name="logam1k" placeholder="Logam Rp. 1.000" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="logam500">Logam Rp. 500</label> --}}
                                                <input type="number" class="form-control" id="logam500"
                                                    name="logam500" placeholder="Logam Rp. 500" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="logam200">Logam Rp. 200</label> --}}
                                                <input type="number" class="form-control" id="logam200"
                                                    name="logam200" placeholder="Logam Rp. 200" min="0">
                                            </div>
                                            <div class="form-group col-md-2">
                                                {{-- <label for="logam100">Logam Rp. 100</label> --}}
                                                <input type="number" class="form-control" id="logam100"
                                                    name="logam100" placeholder="Logam Rp. 100" min="0">
                                            </div>
                                            <div class="form-group col-md-2 d-flex justify-content-between">
                                                <button type="button" id="btnSimpan" class="btn btn-primary"
                                                    onclick="simpanPenutupanKas();">Simpan</button>
                                                <button type="button" id="btnBatal" class="btn btn-secondary"
                                                    onclick="resetForm('tutup');">Batal</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="container-fluid" id="formLayanan">
                                        <div class="p-0 ml-2 card card-warning">
                                            <div class="card-header">
                                                <h3 class="card-title">Data
                                                    Transaksi Penutupan Kas</h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body p-4">
                                                <div class="form-group row">
                                                    <label for="tahun" class="col-form-label col-1">Tahun :</label>
                                                    <div class="col-2">
                                                        <select name="Tahun" id="tahunTutup" class="form-control">
                                                            <option value="all">Semua
                                                            </option>
                                                            @foreach ($listYear as $item)
                                                                <option value="{{ $item }}">{{ $item }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button class="btn btn-success mx-2"
                                                        onclick="getDataPenutupanKas(true)">Cari
                                                        Data Transaksi Penutupan Kas</button>
                                                    <div class="table-responsive mt-2 "
                                                        style="display: block;
                                                        overflow-x: auto; white-space: nowrap;">
                                                        <table id="dataPenutupanKas" name="dataPenutupanKas"
                                                            class="table table-striped table-tight table-hover"
                                                            style="width:100%" cellspacing="0">
                                                            <thead class="bg-secondary">
                                                                <tr>
                                                                    <th width="15%">Aksi</th>
                                                                    <th>No</th>
                                                                    <th>Tanggal Penutupan KAS Sekarang</th>
                                                                    <th>Tanggal Penutupan KAS Lalu</th>
                                                                    <th>Petugas</th>
                                                                    <th>Total Penerimaan</th>
                                                                    <th>Total Pengeluaran</th>
                                                                    <th>Saldo BKU</th>
                                                                    <th>Saldo Kas</th>
                                                                    <th>Selisih Saldo</th>
                                                                    <th>Kertas Rp. 100.000</th>
                                                                    <th>Kertas Rp. 50.000</th>
                                                                    <th>Kertas Rp. 20.000</th>
                                                                    <th>Kertas Rp. 10.000</th>
                                                                    <th>Kertas Rp. 5.000</th>
                                                                    <th>Kertas Rp. 2.000</th>
                                                                    <th>Kertas Rp. 1.000</th>
                                                                    <th>Logam Rp. 1.000</th>
                                                                    <th>Logam Rp. 500</th>
                                                                    <th>Logam Rp. 200</th>
                                                                    <th>Logam Rp. 100</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
