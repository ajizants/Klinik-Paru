<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 class="m-0 font-weight-bold text-dark text-center">TB 04 Laboratorium</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show card-body p-0" id="collapseCardAntrian">
        <div class="col-6 d-flex justify-content-center z-3 position-absolute">
        </div>
        <div class="mt-3">
            @include('Template.Table.loading')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link active bg-blue"
                        onclick="toggleSections('#dTunggu');  $('#divTrans').show(); document.getElementById('divTgl').style.removeProperty('display', 'none', 'important');"><b>Daftar
                            Tunggu</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link"
                        onclick="toggleSections('#dSelesai'); $('#divTrans').show(); document.getElementById('divTgl').style.removeProperty('display', 'none', 'important');"><b>Daftar
                            Selesai</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link"
                        onclick="toggleSections('#tab_3'); $('#divTrans').hide(); document.getElementById('divTgl').style.setProperty('display', 'none', 'important');"><b>Cetak
                            laporan</b></a>
                </li>
                <div class="input-group col d-flex justify-content-end mr-5" id="divTgl">
                    <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal"
                        value="{{ old('date') }}" required onchange="antrian();" />
                    <div class="input-group-addon btn btn-danger">
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian" onclick="antrian();"></span>
                    </div>
                </div>
            </ul>
            <div id="dTunggu" class="card-body card-body-hidden p-2">
                <div class="table-responsive pt-2 px-2">
                    <table id="antrianBelum" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-teal">
                            <tr>
                                <th widh="15px">Aksi</th>
                                <th>Hasil</th>
                                <th>Tanggal</th>
                                <th>Jaminan</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>Alamat Pasien</th>
                                <th>NoReg</th>
                                <th>KD</th>
                                <th>NoSediaan</th>
                                <th>Alasan</th>
                                <th>Pemeriksaan</th>
                                {{-- <th>Dokter</th> --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="dSelesai" class="card-body card-body-hidden p-2" style="display: none;">
                <div class="table-responsive pt-2 px-2">
                    <table id="antrianSudah" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                        cellspacing="0">
                        <thead class="bg bg-secondary">
                            <tr>
                                <th widh="15px">Aksi</th>
                                <th>Hasil</th>
                                <th>Tanggal</th>
                                <th>Jaminan</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>Alamat Pasien</th>
                                <th>NoReg</th>
                                <th>KD</th>
                                <th>NoSediaan</th>
                                <th>Alasan</th>
                                <th>Pemeriksaan</th>
                                {{-- <th>Dokter</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="tab_3" class="card-body card-body-hidden p-0" style="display: none;">
                <div class="container-fluid p-3">
                    <div class="form-row align-items-center">
                        <label for="idTb04" class="col-auto col-form-label font-weight-bold mb-0">
                            No Reg Lab
                        </label>
                        <div class="col-auto">
                            <select name="idTb04Thn" class="form-control" id="idTb04Thn">
                                @foreach (range(2024, date('Y')) as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <input type="text" class="form-control" id="idTb04"
                                placeholder="Batas Awal No Reg Lab" onkeyup="if (event.key === 'Enter') cetakTb04Id();">
                        </div>

                        <div class="col-auto">
                            <a class="btn btn-primary" onclick="cetakTb04Id();">
                                Cetak Laporan TB 04 By Reg Lab
                            </a>
                        </div>
                        <div class="col-auto">
                            Pencarian by No Regl Lab akan berfokus pada No Reg Lab yang di pilih sampai No Reg Lab
                            terakhir. Jika Akan mengambil semua data tuliskan "all" di No Reg Lab
                        </div>
                        <div class="form-row align-items-center">
                            <div class="form-inline d-flex justify-content-start p-2">
                                <label for="tglAwal"><b>Tanggal Awal</b></label>
                                <input type="date" class="form-control m-2" id="tglAwal"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                <label for="tglAkhir" class="form-label"><b>Tanggal Akhir</b></label>
                                <input type="date" class="form-control m-2" id="tglAkhir"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
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
                                var idTb04Thn = document.getElementById("idTb04Thn").value;
                                window.open('/api/tb04/cetakId/' + idTb04 + '/' + idTb04Thn, '_blank');
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>

    </div>
