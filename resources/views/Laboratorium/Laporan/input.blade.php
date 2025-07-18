                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active bg-blue" type="button" id="ikunjungan"
                            onclick="toggleSections('#hasilPemeriksaan');">
                            <b>Laporan Hasil Pemeriksaan</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" id="ijumlah" onclick="toggleSections('#waktuLayanan');">
                            <b>Laporan Waktu Pemeriksaan</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" id="ipoin" onclick="toggleSections('#poin');">
                            <b>Laporan Poin Petugas</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" id="ijumlah" onclick="toggleSections('#jmlhPeriksa');">
                            <b>Laporan Jumlah Pemeriksaan</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" onclick="toggleSections('#tab_1');">
                            <b>Laporan TB 04</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" type="button" onclick="toggleSections('#tab_2');">
                            <b>Laporan Jumlah Pemeriksaan (NEW)</b>
                        </a>
                    </li>

                </ul>

                <div class="container-fluid mt-1" id="parameter">
                    <div class="card card-lime pb-0">
                        <div class="card-header">
                            <h4 class="card-title">Pencarian Laporan</h4>
                        </div>

                        <div class="form-horizontal">
                            <div class="card-body shadow">
                                <div class="form-inline d-flex justify-content-start p-2">
                                    <label for="tglAwal"><b>Tanggal Awal :</b></label>
                                    <input type="date" class="form-control bg bg-warning m-2" id="tglAwal"
                                        value="{{ old('date') }}" required>
                                    <label for="tglAkhir" class="form-label"><b>Tanggal Akhir :</b></label>
                                    <input type="date" class="form-control bg bg-warning m-2" id="tglAkhir"
                                        value="{{ old('date') }}" required>
                                </div>

                                <!-- Hasil Pemeriksaan -->
                                <div class="container-fluid" id="hasilPemeriksaan">
                                    <div id="cariKunjungan">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportKunjungan();">
                                            Cari Laporan Hasil Per Pasien
                                        </a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="reportKunjungan" class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0"></table>
                                    </div>
                                </div>

                                <!-- Waktu Layanan -->
                                <div class="container-fluid" id="waktuLayanan" style="display:none;">
                                    <div id="cariPenjamin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="waktuPemeriksaan();">
                                            Cari Laporan Waktu Pemeriksaan
                                        </a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="tabelRataWaktuLayanan" class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0"></table>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="tabelWaktuLayanan" class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0"></table>
                                    </div>
                                </div>

                                <!-- Poin Petugas -->
                                <div class="container-fluid" id="poin" style="display:none;">
                                    <div id="cariPoin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportPoin();">
                                            Cari Laporan Poin Petugas
                                        </a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="reportPoin" class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Pemeriksaan</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Jumlah Periksa -->
                                <div class="container-fluid" id="jmlhPeriksa" style="display:none;">
                                    <div id="cariPenjamin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportJumlahPemeriksaan();">
                                            Cari Laporan Jumlah Pemeriksaan
                                        </a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <table id="tabelJumlahPeriksa" class="table table-striped pt-0 mt-0 fs-6"
                                            style="width:100%" cellspacing="0"></table>
                                    </div>
                                </div>
                                <!-- Jumlah TB04 -->
                                <div class="container-fluid" id="tab_1" style="display:none;">
                                    {{-- <div id="cariTb04">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="cetakTb04();">
                                            Cetak Laporan TB 04 By TGL
                                        </a>
                                    </div> --}}
                                    <div id="cariTb04" class="form-row align-items-center">
                                        <label for="idTb04" class="col-auto col-form-label font-weight-bold mb-0">
                                            Batas Awal No Reg Lab
                                        </label>
                                        <div class="col-auto">
                                            <input type="text" class="form-control bg-warning" id="idTb04"
                                                onkeyup="if (event.key === 'Enter') cetakTb04Id();">
                                        </div>

                                        <div class="col-auto">
                                            <a class="btn btn-primary" onclick="cetakTb04Id();">
                                                Cetak Laporan TB 04 By ID
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-success" onclick="cetakTb04();">
                                                Cetak Laporan TB 04 By Tanggal (Pilih tgl awal dan akhir)
                                            </a>
                                        </div>
                                    </div>

                                    <script>
                                        function cetakTb04() {
                                            var tglAwal = document.getElementById("tglAwal").value;
                                            var tglAkhir = document.getElementById("tglAkhir").value;
                                            window.open('/api/tb04/cetak/' + tglAwal + '/' + tglAkhir, '_blank');
                                        }

                                        function cetakTb04Id() {
                                            var idTb04 = document.getElementById("idTb04").value;
                                            window.open('/api/tb04/cetak/' + idTb04, '_blank');
                                        }
                                    </script>

                                </div>

                                <!-- Jumlah Periksa NEW-->
                                <div class="container-fluid" id="tab_2" style="display:none;">
                                    <div id="cariPenjamin">
                                        <a class="btn btn-success d-flex justify-content-center mx-2"
                                            onclick="reportJumlahPemeriksaanSingle();">
                                            Cari Laporan Jumlah Pemeriksaan (NEW)
                                        </a>
                                    </div>
                                    <div class="table-responsive pt-2 px-2">
                                        <div class="" id="divTabelJmlPemeriksaanNew"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
