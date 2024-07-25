                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" type="button" id="ikunjungan"
                            onclick="toggleSections('#hasilPemeriksaan');"><b>Laporan
                                Hasil Pemeriksaan</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" id="ipoin" onclick="toggleSections('#poin');"><b>Laporan
                                Poin
                                Petugas</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" id="ijumlah"
                            onclick="toggleSections('#jmlhPeriksa');"><b>Laporan
                                Jumlah Pemeriksaan</b></a>
                    </li>

                </ul>
                <div class="container-fluid mt-1" id="parameter">
                    <div class="card card-lime pb-0">
                        <div class="card-header">
                            <h4 class="card-title">Pencarian Laporan</h4>
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
                                </div>
                                <div class="container-fluid" id="hasilPemeriksaan">
                                    <div id="cariKunjungan">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportKunjungan();">Cari Laporan Hasil Per Pasien</a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="reportKunjungan"class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0">

                                    </div>
                                </div>
                                <div class="container-fluid" id="poin" style="display:none;">
                                    <div id="cariPoin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportPoin();">Cari Laporan Poin Petugas</a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="reportPoin"class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0">
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
                                <div class="container-fluid" id="jmlhPeriksa" style="display:none;">
                                    <div id="cariPenjamin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportJumlahPemeriksaan();">Cari Laporan Jumlah Pemeriksaan</a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="tabelJumlahPeriksa"class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0">
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
