<div class="row">
    <div class="col">
        <div class="card-success">
            <div class="card-header">
                <h3 class="card-title">Input Data Perkembangan Pasien Rawat Inap</h3>

            </div>
            @csrf
            <form id="form_cppt">
                <div hidden>
                    <input type="text" id="norm" name="norm">
                    <input type="text" id="notrans" name="notrans">
                </div>
                <div class="card-body">
                    <label>Antropometri</label>
                    <div class="row mb-2">
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="td" name="td"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="TD" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        mmHg
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="nadi" name="nadi"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="Nadi" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        x/mnt
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="suhu" name="suhu"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="Suhu" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <sup>o</sup>C
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="rr" name="rr"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="RR" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        x/mnt
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="bb" name="bb"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="BB" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        kg
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="tb" name="tb"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="TB" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        cm
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="bbi" name="bbi"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="BBI" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        kg
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="lla" name="lla"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="LLA" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        cm
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="imt" name="imt"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm" placeholder="IMT" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        kg/m<sup>2</sup>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <select type="text" inputmode="numeric" id="status_gizi" name="status_gizi"
                                    class="form-control" aria-describedby="inputGroup-sizing-sm"
                                    placeholder="Status Gizi">
                                    <option value="">--Pilih Status Gizi--</option>
                                    <option value="Kekurangan BB Tingkat Berat">Kekurangan BB Tingkat Berat
                                    </option>
                                    <option value="Kekurangan BB Tingkat Ringan">Kekurangan BB Tingkat Ringan
                                    </option>
                                    <option value="BB Normal">Berat Badan Normal</option>
                                    <option value="Kelebihan BB Tingkat Ringan">Kelebihan BB Tingkat Ringan
                                    </option>
                                    <option value="Kelebihan BB Tingkat Berat">Kekuatan BB Tingkat Berat</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <label>DS & DO</label>
                    <div class="row">
                        <div class="col-md mx-1">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Data Subjektif
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <textarea id="objektif" name="objektif" placeholder="Tuliskan Data Subjektif Pasien di sini"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md mx-1">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Data Objektif
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <textarea id="subjektif" name="subjektif" placeholder="Tuliskan Data Objektif Pasien di sini"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <label>Assesment & Planing</label>
                    {{-- <div class="row"> --}}
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="nav-1-tab" data-toggle="tab" href="#nav-1"
                                        role="tab" aria-controls="nav-1" aria-selected="true">Dokter</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-tindakan-tab" data-toggle="tab" href="#nav-tindakan"
                                        role="tab" aria-controls="nav-tindakan" aria-selected="true">Tindakan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-2-tab" data-toggle="tab" href="#nav-2"
                                        role="tab" aria-controls="nav-2" aria-selected="false">RO & Lab</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-3-tab" data-toggle="tab" href="#nav-3"
                                        role="tab" aria-controls="nav-3" aria-selected="false">Nutritionis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-4-tab" data-toggle="tab" href="#nav-4"
                                        role="tab" aria-controls="nav-4" aria-selected="false">Terapis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-5-tab" data-toggle="tab" href="#nav-5"
                                        role="tab" aria-controls="nav-5" aria-selected="false">Apoteker</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body mx-0 px-0 py-1">
                            {{-- <div class="form-row mx-0 px-0 py-1"> --}}
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="nav-1" role="tabpanel"
                                    aria-labelledby="nav-1-tab">
                                    <!-- Konten Tab 1 -->
                                    @include('Ranap.Cppt.assDokter')
                                </div>
                                <div class="tab-pane fade" id="nav-tindakan" role="tabpanel"
                                    aria-labelledby="nav-tindakan-tab">
                                    <!-- Konten Tab 1 -->
                                    @include('Ranap.Cppt.Planing.tindakan')
                                </div>
                                <div class="tab-pane fade" id="nav-2" role="tabpanel"
                                    aria-labelledby="nav-2-tab">
                                    <!-- Konten Tab 2 -->
                                    @include('Ranap.Cppt.roLab')
                                </div>
                                <div class="tab-pane fade" id="nav-3" role="tabpanel"
                                    aria-labelledby="nav-3-tab">
                                    <!-- Konten Tab 3 -->
                                    @include('Ranap.Cppt.assGizi')
                                </div>
                                <div class="tab-pane fade" id="nav-4" role="tabpanel"
                                    aria-labelledby="nav-4-tab">
                                    <!-- Konten Tab 4 -->
                                    @include('Ranap.Cppt.assTerapis')
                                </div>
                                <div class="tab-pane fade" id="nav-5" role="tabpanel"
                                    aria-labelledby="nav-5-tab">
                                    <!-- Konten Tab 4 -->
                                    @include('Ranap.Cppt.assApoteker')
                                </div>
                            </div>

                            {{-- </div> --}}
                            {{-- @include('Ranap.Cppt.Planing.tindakan') --}}
                        </div>
                    </div>

                    <div class="form-row d-flex justify-content-end">
                        <label for="petugas" class="col-auto col-form-label font-weight-bold">Pembuat CPPT
                            :</label>
                        <select id="petugas" name="petugas"
                            class="col-3 mx-2 select2bs4 form-control border border-primary" required>
                            <option value="">--Pilih Pembuat CPPT--</option>
                            @foreach ($petugas as $item)
                                <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                    {{ $item->nama }} {{ $item->gelar_b }}</option>
                            @endforeach
                        </select>
                        <div class="form-group row mx-2">
                            <div class="col-auto">
                                <a class="btn btn-sm btn-primary" id="tombol_selesai"
                                    onclick="simpanCppt();">Simpan</a>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-sm btn-warning" id="tblBatal" onclick="reset();">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card-info">
            <div class="card-header">
                <h3 class="card-title">Riwayat Kunjungan Pasien</h3>
            </div>
            <div class="card-body">
                <table id="tabel_kunjungan" class="table table-bordered table-striped" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>Aksi</th>
                            <th>Tanggal</th>
                            <th>Professional Pemberi Ashuan</th>
                            <th>Hasil Assessment Pasien & Pemberian Pelayanan</th>
                            <th>Intruksi PPA</th>
                            <th>Review & Verifikasi DPJP</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
