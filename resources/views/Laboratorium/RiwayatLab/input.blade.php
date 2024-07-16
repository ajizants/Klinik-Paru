                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" type="button" id="ikunjungan" onclick="showKunjungan();"><b>Laporan
                                Kunjungan</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" id="ipoin" onclick="showPoin();"><b>Laporan Poin
                                Petugas</b></a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="ipenjamin" onclick="showPenjamin();"><b>Laporan Penjamin</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ireagen" onclick="showReagen();"><b>Laporan Reagen</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ihasil" onclick="showHasil();"><b>Laporan Hasil Pemeriksaan</b></a>
                    </li> --}}

                </ul>
                <div class="container-fluid mt-1" id="parameter">
                    <div class="card card-lime pb-0">
                        <div class="card-header">
                            <h4 class="card-title">Parameter Pencarian Laporan</h4>
                        </div>
                        @csrf
                        <form class="form-horizontal">
                            <div class="card-body shadow">
                                <div class="form-inline d-flex justify-content-start p-2">
                                    <label for="tglAwal"> <b>Tanggal Awal :</b></label>
                                    <input type="date" class="form-control bg bg-warning m-2" id="tglAwal"
                                        value="{{ old('date') }}" required>
                                    <label for="tglAkhir" class="form-label"><b>Tanggal Akhir
                                            :</b></label>
                                    <input type="date" class="form-control bg bg-warning m-2" id="tglAkhir"
                                        value="{{ old('date') }}" required>
                                    <label for="jaminan" class="form-label" hidden><b>Jaminan
                                            :</b></label>
                                    <select type="date" class="form-control bg bg-warning m-2" id="jaminan" hidden>
                                        <option value="">--Pilih Jaminan--</option>
                                    </select>
                                    <div id="cariKunjungan">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportKunjungan();">Cari</a>
                                    </div>
                                    <div id="cariPoin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportPoin();">Cari</a>
                                        {{-- </div>
                                    <div id="cariPenjamin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportPenjamin();">Cari</a>
                                    </div>
                                    <div id="cariReagen">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportReagen();">Cari Per Hari</a>
                                    </div>
                                    <div id="cariReagenBln">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportReagenBln();">Cari Per Bulan</a>
                                    </div>
                                    <div id="cariHasil">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportHasil();">Cari</a>
                                    </div> --}}

                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <div class="container-fluid" id="kunjungan">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Kunjungan</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="table-responsive pt-2 px-2">
                                <table id="reportKunjungan"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>NoRM</th>
                                            <th>NIK</th>
                                            <th>Jaminan</th>
                                            <th>Pasien</th>
                                            <th>Alamat</th>
                                            <th>Dokter</th>
                                            <th>Pemeriksaan</th>
                                            <th>Petugas</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="container-fluid" id="penjamin">
                    <div class="card card-info">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Kunjungan Berdasarkan Penjamin</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="table-responsive pt-2 px-2">
                                <table id="reportPenjamin"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Pemeriksaan</th>
                                            <th>Jumlah</th>
                                            <th>Jaminan</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="container-fluid" id="reagen">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Jumlah total pemeriksaan (penggunaan reagen)</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="table-responsive pt-2 px-2">
                                <table id="reportReagen"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Pemeriksaan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="container-fluid" id="hasil">
                    <div class="card card-success">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Hasil Pemeriksaan</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="table-responsive pt-2 px-2">
                                <table id="reportHasil"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Pemeriksaan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="container-fluid" id="poin">
                    <div class="card card-orange">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Jumlah Pemeriksaan Petugas</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="table-responsive pt-2 px-2">
                                <table id="reportPoin"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Pemeriksaan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
