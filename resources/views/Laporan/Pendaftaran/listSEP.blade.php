<div class="" id="tab_2" style="display: none;">
    <div class="container-fluid d-flex justify-content-center bg-warning border">
        <h5 class="m-2"><b>Data SEP dan Surat Kontrol</b></h5>
    </div>
    <div class="container-fluid ">
        <div class="my-2" id="formCetakSurat">
            <div class="col-12">
                <ul>
                    {{-- <li>Tulis No SEP saat mencari SEP</li>
                    <li>Tulis No Surat Kontrol saat mencari Surat Kontrol</li> --}}
                    <li>Tulis Nomor MR saat mencari Surat Rujukan Baru atau Surat Selesai Pengobatan</li>

                </ul>
            </div>
            <div class="form-grup row">
                <input type="date" name="Mulai Tanggal" id="tglSurat" placeholder="Mulai Tanggal"
                    class="form-control  col-2" value="{{ date('Y-m-d') }}" />
                <select name="Jenis Surat" id="jenisSurat" class="form-control  col-2">
                    <option value="rb">Surat Rujukan Baru</option>
                    <option value="sp">Surat Selesai Pengobatan</option>
                    <option value="sep">SEP</option>
                    <option value="sk">Surat Kontrol</option>
                </select>
                <input type="text" name="Nomor MR" id="idSurat" placeholder="Nomor MR / No SEP"
                    class="form-control  col-3" />
                <div class="col-1">
                    <button class="btn btn-primary"
                        onclick="cetakSurat($('#jenisSurat').val(), $('#idSurat').val(), $('#tglSurat').val())">Cari</button>
                </div>
                <script type="text/javascript">
                    function cetakSurat(jenisSurat, idSurat, tglSurat) {
                        switch (jenisSurat) {
                            case "sep":
                                window.open("/api/sep/cetak/" + idSurat);
                                break;
                            case "sk":
                                window.open("/api/rujukan/cetak/" + tglSurat + "/" + idSurat);
                                break;
                            case "rb":
                                window.open("/api/rujukan_baru/cetak/" + tglSurat + "/" + idSurat);
                                break;
                            case "sp":
                                window.open("/api/rujukan/cetak/" + tglSurat + "/" + idSurat);
                                break;
                        }
                    }
                </script>
                <div class="table-responsive  mt-2">
                    <table class="table table-bordered table-hover dataTable dtr-inline" id="tableSEP" cellspacing="0">
                        <thead class="bg bg-teal table-bordered border-warning">
                            <tr>
                                <th class="col-2">Aksi</th>
                                <th>Urut</th>
                                <th>Tanggal</th>
                                <th>Detail SEP</th>
                                <th>Detai Surat Kontrol</th>
                                <th>Kunjungan</th>
                                <th>Daftar By</th>
                                <th>No. RM</th>
                                <th class="col-2">Nama Pasien</th>
                                <th>Poli</th>
                                <th class="col-3">Dokter</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered border-warning">
                        </tbody>
                    </table>
                </div>
            </div>
