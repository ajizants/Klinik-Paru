<div class="container-fluid form-row">
    <div class="col-5 card shadow">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
            <h6 class="m-0 font-weight-bold text-primary">Form Pendaftaran Pasien Rawat Inap</h6>
        </div>
        <div class="card-body">
            <form class="form-horizontal" id="formPendaftaran">
                <h5>Identitas Pasien</h5>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>No. RM</label>
                        <div class="input-group">
                            <input type="text" name="pasien_no_rm" id="pasien_no_rm" class="form-control"
                                placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required />
                            <div class="input-group-addon btn btn-warning">
                                <span data-toggle="tooltip" data-placement="top"
                                    title="Jika Pasien Baru, Daftarkan Dahulu Di Aplikasi KOMINFO" id="cariPasien"
                                    onclick="lihatIdentitas();">Cari</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nama</label>
                        <input readonly type="text" class="form-control" name="pasien_nama" id="pasien_nama">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>NIK</label>
                        <input readonly type="text" class="form-control" name="pasien_nik" id="pasien_nik">
                    </div>
                    <div class="form-group col-md-6">
                        <label>No. KK</label>
                        <input readonly type="text" class="form-control" name="pasien_no_kk" id="pasien_no_kk">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Jenis Kelamin</label>
                        <input readonly type="text" class="form-control" name="jenis_kelamin_nama"
                            id="jenis_kelamin">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tempat Lahir</label>
                        <input readonly type="text" class="form-control" name="pasien_tempat_lahir"
                            id="tempat_lahir">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tanggal Lahir</label>
                        <input readonly type="date" class="form-control" name="pasien_tgl_lahir" id="tanggal_lahir">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>No. HP</label>
                        <input readonly type="text" class="form-control" name="pasien_no_hp" id="no_hp">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Status Kawin</label>
                        <input readonly type="text" class="form-control" name="status_kawin_nama" id="status_kawin">
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Domisili</label>
                    <input readonly type="text" class="form-control" name="pasien_domisili" id="domisili">
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input readonly type="text" class="form-control" name="pasien_alamat" id="alamat">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Provinsi</label>
                        <input readonly type="text" class="form-control" name="provinsi_nama" id="provinsi">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Kabupaten</label>
                        <input readonly type="text" class="form-control" name="kabupaten_nama" id="kabupaten">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Kecamatan</label>
                        <input readonly type="text" class="form-control" name="kecamatan_nama" id="kecamatan">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Kelurahan</label>
                        <input readonly type="text" class="form-control" name="kelurahan_nama" id="kelurahan">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>RT</label>
                        <input readonly type="text" class="form-control" name="pasien_rt" id="rt">
                    </div>
                    <div class="form-group col-md-2">
                        <label>RW</label>
                        <input readonly type="text" class="form-control" name="pasien_rw" id="rw">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Agama</label>
                        <input readonly type="text" class="form-control" name="agama_nama" id="agama">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Gol. Darah</label>
                        <input readonly type="text" class="form-control" name="goldar_nama" id="gol_darah">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Pendidikan</label>
                        <input readonly type="text" class="form-control" name="pendidikan_nama" id="pendidikan">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Pekerjaan</label>
                        <input readonly type="text" class="form-control" name="pekerjaan_nama" id="pekerjaan">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Penjamin</label>
                        <input readonly type="text" class="form-control" name="penjamin_nama" id="penjamin">
                    </div>
                    <div class="form-group col-md-6">
                        <label>No. Penjamin</label>
                        <input readonly type="text" class="form-control" name="penjamin_nomor"
                            id="penjamin_nomor">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Daftar By</label>
                        <input readonly type="text" class="form-control" name="pasien_daftar_by" id="daftar_by">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal Registrasi</label>
                        <input readonly type="date" class="form-control" name="created_at_tanggal"
                            id="created_at">
                    </div>
                </div>

                <h5>Data Pendaftaran</h5>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="p_jawab" class="col-auto col-form-label font-weight-bold mb-0">Penanggung Jawab
                            :</label>
                        <input type="text" id="p_jawab" name="p_jawab" class="form-control " />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="hub_p_jawab" class="col-auto col-form-label font-weight-bold mb-0">Hubungan
                            :</label>
                        <input type="text" id="hub_p_jawab" name="hub_p_jawab" class="form-control " />
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="jaminan" class="col-auto col-form-label font-weight-bold mb-0">Jaminan
                            :</label>
                        <select id="jaminan" name="jaminan" class="form-control select2bs4">
                            <option value="">--Pilih Jaminan--</option>
                            <option value="BPJS">BPJS</option>
                            <option value="UMUM">UMUM</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tgl_masuk" class="col-auto col-form-label font-weight-bold mb-0">Tanggal
                            :</label>
                        <input type="date" id="tgl_masuk" name="tgl_masuk"
                            class="form-control form-control-sm bg-white" placeholder="Tanggal Transaksi"
                            value="{{ date('Y-m-d') }}" />
                    </div>
                    <div class="form-group col-md-4">
                        <label for="ruang" class="col-auto col-form-label font-weight-bold mb-0">Ruangan
                            :</label>
                        <select name="ruang" id="ruang" class="form-control select2bs4">
                            <option value="">--Pilih Ruang--</option>
                            @foreach ($ruangan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_ruangan }}</option>
                            @endforeach


                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="admin" class="col-auto col-form-label font-weight-bold">Admin
                            :</label>
                        <select id="admin" name="admin" class="form-control border border-primary" required>
                            <option value="">--Pilih Petugas--</option>
                            @foreach ($petugas as $item)
                                <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                    {{ $item->nama }} {{ $item->gelar_b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dpjp" class="col-auto col-form-label font-weight-bold">Dokter
                            :</label>
                        <select id="dpjp" name="dpjp" class="form-control border border-primary" required>
                            <option value="">--Pilih DPJP--</option>
                            @foreach ($dokter as $item)
                                <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                    {{ $item->nama }} {{ $item->gelar_b }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class=" form-group d-flex justify-content-center">
                    <button type="button" class="btn btn-primary col" data-toggle="modal"
                        data-target="#riwayatModal" onclick="simpanPendaftaran();">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-7 card shadow">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
            <h6 class="m-0 font-weight-bold text-primary">Pasien Rawat Inap</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="divTablePasienRanap">

            </div>
        </div>
    </div>
