<div class="row">
    <div class="col-md-4">
        <div class="card-success">
            <div class="card-header">
                <h3 class="card-title">Kunjungan Pasien</h3>
            </div>
            @csrf
            <form>
                <div class="card-body">
                    <div class="col">
                        <div class="input-group input-group-sm mb-2 row">
                            <input type="text" inputmode="numeric" id="bb" class="form-control col"
                                aria-describedby="inputGroup-sizing-sm" placeholder="BB" step="1" />
                            <div class="input-group-append mr-2">
                                <div class="input-group-text">
                                    kg
                                </div>
                            </div>
                            <input type="text" inputmode="numeric" id="tb" class="form-control col"
                                aria-describedby="inputGroup-sizing-sm" placeholder="TB" step="1" />
                            <div class="input-group-append mr-2">
                                <div class="input-group-text">
                                    cm
                                </div>
                            </div>
                            <input type="text" inputmode="numeric" id="imt" class="form-control col"
                                aria-describedby="inputGroup-sizing-sm" placeholder="IMT" step="1" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    kg/m<sup>2</sup>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <textarea type="text" id="keluhan" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Keluhan Pasien" required style="height: 100px;"></textarea>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <textarea type="text" id="parameter" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Parameter yang dimonitor" required style="height: 100px;"></textarea>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <select type="select" id="dxMedis" aria-describedby="inputGroup-sizing-sm"
                            class="form-control select2bs4 mb-2 border border-primary" required>
                            <option value="">--Pilih Diagnosa Medis--</option>
                            @foreach ($dxMed as $dx)
                                <option value="{{ $dx->kdDiag }}">{{ $dx->diagnosa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <select type="select"id="dxGizi" aria-describedby="inputGroup-sizing-sm"
                            class="form-control select2bs4 mb-2 border border-primary" required>
                            <option value="">--Pilih Diagnosa Gizi--</option>
                            @foreach ($sub as $dx)
                                <option value="{{ $dx->kode }}">{{ $dx->sub_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" id="etiologi" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Etiologi Diagnosa" />
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <textarea type="text" id="evaluasi" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Evaluasi ahli gizi" required style="height: 100px;"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="form-grup row">
                            <div class="col-auto">
                                <a class="btn btn-primary" id="tombol_selesai"
                                    onclick="validasi('kunjungan');">Simpan</a>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-warning" id="tblBatal" onclick="batal();">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="col">
        <div class="card-info">
            <div class="card-header">
                <h3 class="card-title">Riwayat Kunjungan Pasien</h3>
            </div>
            <div class="card-body">
                <table id="kunjungan" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th class="text-center">BB (kg)</th>
                            <th class="text-center">TB (cm)</th>
                            <th class="text-center">IMT (kg/m<sup>2</sup>)</th>
                            <th class="text-center">Parameter yang dimonitor</th>
                            <th class="text-center">Diagnosis Gizi</th>
                            <th class="text-center">Evaluasi Ahli Gizi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
