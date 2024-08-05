<div class="card-success">
    <div class="card-header">
        <h3 class="card-title">Kunjungan Pasien</h3>
    </div>
    @csrf
    <form id="form_asesment">
        <div class="card-body">
            <label>Riwayat Gizi</label>
            <div class="row mb-2">
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm pb-1">
                        <input type="text" id="frek_makan" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Frek. Makan Utama" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                kali/hari
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" id="frek_selingan" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Frek. Makan Selingan" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                kali/hari
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea type="text" id="makanan_selingan" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Sebutkan Makanan Selingan" style="height: 63px;"></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea type="text" id="alergi_makanan" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Sebutkan Alergi Makanan" style="height: 63px;"></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea type="text" id="pantangan_makanan" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Sebutkan Pantangan Makanan" style="height: 63px;"></textarea>
                    </div>
                </div>
            </div>

            <label>Bahan Makanan Yang Biasa Dikonsumsi</label>
            <div class="row mb-2">
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea name="" cols="2" rows="2" type="text" id="makanan_pokok" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Sebutkan Makanan Pokok"></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea name="" cols="2" rows="2" type="text" id="lauk_hewani" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Sebutkan Lauk Hewani"></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea name="" cols="2" rows="2" type="text" id="lauk_nabati" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Sebutkan Lauk Nabati"></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea name="" cols="2" rows="2" type="text" id="sayuran" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Sebutkan Sayuran yang biasa dikonsumsi"></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea name="" cols="2" rows="2" type="text" id="buah" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Sebutkan Buah yang biasa dikonsumsi"></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea name="" cols="2" rows="2" type="text" id="minuman" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Sebutkan Minuman yang biasa dikonsumsi"></textarea>
                    </div>
                </div>
            </div>
            <label>Antropometri</label>
            <div class="row mb-2">
                <div class="col-md-2 pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" inputmode="numeric" id="bb_awal" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="BB" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                kg
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" inputmode="numeric" id="bbi" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="BBI" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                kg
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" inputmode="numeric" id="tb_awal" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="TB" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                cm
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" inputmode="numeric" id="lla" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="LLA" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                cm
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" inputmode="numeric" id="imt_awal" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="IMT" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                kg/m<sup>2</sup>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <select type="text" inputmode="numeric" id="status_gizi" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Status Gizi">
                            <option value="">--Pilih Status Gizi--</option>
                            <option value="Kekurangan BB Tingkat Berat">Kekurangan BB Tingkat Berat</option>
                            <option value="Kekurangan BB Tingkat Ringan">Kekurangan BB Tingkat Ringan</option>
                            <option value="BB Normal">Berat Badan Normal</option>
                            <option value="Kelebihan BB Tingkat Ringan">Kelebihan BB Tingkat Ringan</option>
                            <option value="Kelebihan BB Tingkat Berat">Kekuatan BB Tingkat Berat</option>

                        </select>
                    </div>
                </div>

            </div>

            <label>Klinis/Fisik</label>

            <div class="row mb-2">
                <div class="col-md-4 pr-1 pl-0 pb-1">
                    <select class="select2Multi" multiple="multiple" name="keluhan[]" id="keluhan_awal"
                        aria-placeholder="Keluhan">
                        <option value="Mual">Mual</option>
                        <option value="Muntah">Muntah</option>
                        <option value="Batuk">Batuk</option>
                        <option value="Sesak Nafas">Sesak Nafas</option>
                        <option value="Tidak Ada Nafsu Makan">Tidak Ada Nafsu Makan</option>
                    </select>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <div class="col-md pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" id="td" class="form-control"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Tekanan Darah" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        mmHg
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="nadi" class="form-control"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Frek. Nadi" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        x/menit
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="rr" class="form-control"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Nafas" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        x/menit
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 pr-1 pl-0 pb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" inputmode="numeric" id="suhu" class="form-control"
                                    aria-describedby="inputGroup-sizing-sm" placeholder="Suhu" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <sup>o</sup>C
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 col-md-6 col-lg pr-1 pl-0 pb-1 mb-2">
                    <label for="hasil_lab">Hasil Pemeriksaan Laboratorium</label>
                    <div class="input-group input-group-sm">
                        <textarea class="form-control" id="hasil_lab" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Tulis Hasil Pemeriksaan Lab Jika Ada"></textarea>
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg pr-1 pl-0 pb-1 mb-2">
                    <label for="riwayat_diet_penyakit">Riwayat Gizi Diet / Penyakit</label>
                    <div class="input-group input-group-sm">
                        <textarea class="form-control" id="riwayat_diet_penyakit" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Tulis Riwayat DIet / Penyakit Jika Ada"></textarea>
                    </div>
                </div>
                <div class="form-group col-12 col-md-6 col-lg pr-1 pl-0 pb-1 mb-2">
                    <label for="catatan">Catatan / Informasi Lain Jika Ada</label>
                    <div class="input-group input-group-sm">
                        <textarea class="form-control" id="catatan" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Tulis Catatan / Informasi Lain Jika Ada"></textarea>
                    </div>
                </div>
            </div>
            <label>Diagnosa Gizi</label>
            <div class="row mb-2">
                <div class="col-md-4 pr-1 pl-0 pb-1">
                    <select type="select" id="dxMedis_awal"
                        class="form-control select2bs4 mb-2 border border-primary" required>
                        <option value="">--Pilih Diagnosa Medis--</option>
                        @foreach ($dxMed as $dx)
                            <option value="{{ $dx->kdDiag }}">{{ $dx->diagnosa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 pr-1 pl-0 pb-1">
                    <select type="select" id="dxGizi_awal"
                        class="form-control select2bs4 mb-2 border border-primary" required>
                        <option value="">--Pilih Diagnosa Gizi--</option>
                        @foreach ($sub as $dx)
                            <option value="{{ $dx->kode }}">{{ $dx->sub_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm pb-1">
                        <input type="text" id="etiologi_awal" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Etiologi Diagnosa" />
                    </div>
                </div>
            </div>
            <label>Intervensi Gizi</label>

            <div class="row mb-2">
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm pb-1">
                        <textarea rows="3" type="text" id="diit" class="form-control" aria-describedby="inputGroup-sizing-sm"
                            placeholder="Diit yang dilakukan" required></textarea>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <textarea rows="3" type="text" id="perinsip_diit" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Perinsip diit yang dilakukan" required></textarea>
                    </div>
                </div>
            </div>
            <label>Perincian Energi dan Zat Gizi </label>
            <div class="row mb-2">
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" id="energi" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Energi" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                kkal
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" id="protein" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Protein" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                g
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" id="lemak" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="lemak" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                g
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md pr-1 pl-0 pb-1">
                    <div class="input-group input-group-sm">
                        <input type="text" id="karbohidrat" class="form-control"
                            aria-describedby="inputGroup-sizing-sm" placeholder="Karbohidrat" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                g
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="card-footer">
        <div class="form-grup row d-flex justify-content-end">
            <div class="col-auto">
                <a class="btn btn-primary" id="tombol_selesai" onclick="validasi('asesment');">Simpan</a>
            </div>
            <div class="col-auto">
                <a class="btn btn-warning" id="tblBatal" onclick="resetForm();">Batal</a>
            </div>

        </div>
    </div>

</div>
