                {{-- input tindakan --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Transaksi</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2">
                            <div class="container-fluid">
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Identitas</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    @csrf
                                    <form class="form-horizontal"id="form_identitas">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="norm"
                                                    class="col-sm-1 col-form-label d-none d-md-block font-weight-bold mb-0 ">No
                                                    RM
                                                    :</label>
                                                <div class="col-sm-2 input-group">
                                                    <input type="number" name="norm" id="norm"
                                                        class="form-control" placeholder="No RM" maxlength="6"
                                                        pattern="[0-9]{6}" onkeyup="" />
                                                    <div class="input-group-addon btn btn-danger d-none d-md-block">
                                                        <span class="fa-solid fa-magnifying-glass" onclick="cariTsRo();"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Selain Pasien Hari ini"></span>
                                                    </div>
                                                </div>
                                                <label for="layanan"
                                                    class="col-sm-1 col-form-label d-none d-md-block font-weight-bold mb-0">Layanan
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <select name="layanan" id="layanan" class="form-control select2"
                                                        required>
                                                        <option value="">--Pilih Penjamin--
                                                        <option value="UMUM">UMUM
                                                        <option value="BPJS">BPJS
                                                        </option>
                                                    </select>
                                                </div>
                                                <label for="nama"
                                                    class="col-sm-1 col-form-label d-none d-md-block font-weight-bold  mb-0">Nama
                                                    :</label>
                                                <div class="col-sm-4">
                                                    <input type="text" id="nama" class="form-control bg-white"
                                                        placeholder="Nama Pasien">
                                                </div>
                                                <div class="col-sm-1 mt-md-0 mt-3">
                                                    <input type="text" id="jk" class="form-control bg-white"
                                                        placeholder="JK" />
                                                </div>
                                            </div>
                                            <div class="form-group row mt-2">
                                                <label for="tglRo"
                                                    class="col-sm-1 col-form-label d-none d-md-block font-weight-bold mb-0">Tanggal
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="date" id="tglRo" class="form-control bg-white"
                                                        placeholder="tglRo" />
                                                </div>
                                                <label for="notrans"
                                                    class="col-sm-1 col-form-label d-none d-md-block font-weight-bold mb-0">NoTran
                                                    :</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="notrans" class="form-control bg-white"
                                                        placeholder="Nomor Transaksi" readonly />
                                                </div>
                                                <label for="alamat"
                                                    class="col-sm-1 col-form-label d-none d-md-block font-weight-bold mb-0">Alamat
                                                    :</label>
                                                <div class="col-sm-5">
                                                    <input id="alamat" class="form-control bg-white"
                                                        placeholder="Alamat Pasien" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </form>

                                </div>
                            </div>
                            @csrf
                            <form class="container-fluid" id="formtrans" enctype="multipart/form-data">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Hasil Pemotretan</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Row for Pasien Rawat -->
                                        <div class="form-group row align-items-center">
                                            <label class="col-md-1 col-sm-4 col-form-label" for="pasienRawat">Pasien
                                                Rawat</label>
                                            <div class="col-md-5 col-sm-8">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="pasienRawat"
                                                        value="0" checked>
                                                    <label class="form-check-label" for="pasienRawat0">IRJA</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="pasienRawat"
                                                        value="1">
                                                    <label class="form-check-label" for="pasienRawat1">IGD /
                                                        IRNA</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 border border-3 border-dark bg-warning py-2">
                                                Permintaan RO:
                                                <p id="permintaan" class="fw-bold fs-4"></p>
                                            </div>
                                        </div>

                                        <!-- Row for No. Reg and Selections -->
                                        <div class="form-row mt-md-3 mt-0">
                                            <div class="col-md-1 col-sm-2">
                                                <label for="noreg" class="col-form-label">No. Reg.</label>
                                                <input type="text" name="noreg" maxlength="6"
                                                    class="form-control form-control-sm" placeholder="No. Reg."
                                                    required>
                                            </div>
                                            <div class="col-md-4 col-sm">
                                                <label for="kdFoto" class="col-form-label">Nama Foto</label>
                                                <select name="kdFoto" id="kdFoto" class="form-control select2bs4"
                                                    required>
                                                    <option value="">--Pilih Foto--</option>
                                                    @foreach ($foto as $foto)
                                                        <option value="{{ $foto->kdFoto }}">{{ $foto->nmFoto }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md">
                                                <label for="kdFilm" class="col-form-label">Ukuran Film</label>
                                                <select name="kdFilm" id="kdFilm" class="form-control select2bs4"
                                                    required>
                                                    <option value="">--Pilih Ukuran Film--</option>
                                                    @foreach ($film as $film)
                                                        <option value="{{ $film->kdFilm }}">{{ $film->ukuranFilm }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md">
                                                <label for="kdProyeksi" class="col-form-label">Proyeksi</label>
                                                <select name="kdProyeksi" id="kdProyeksi"
                                                    class="form-control select2bs4" required>
                                                    <option value="">--Pilih Proyeksi--</option>
                                                    @foreach ($proyeksi as $proyeksi)
                                                        <option value="{{ $proyeksi->kdProyeksi }}">
                                                            {{ $proyeksi->proyeksi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Row for Kondisi Selection -->
                                        <div class="form-row mt-md-3 mt-0">
                                            <div class="col-md-2">
                                                <label for="kv" class="col-form-label">Kondisi (KV)</label>
                                                <select name="kv" id="kv"
                                                    class="form-control select2bs4">
                                                    <option value="">--Pilih KV--</option>
                                                    @foreach ($kv as $kv)
                                                        <option value="{{ $kv->kdKondisiRo }}">{{ $kv->nmKondisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="ma" class="col-form-label">Kondisi (mA)</label>
                                                <select name="ma" id="ma"
                                                    class="form-control select2bs4">
                                                    <option value="">--Pilih mA--</option>
                                                    @foreach ($ma as $ma)
                                                        <option value="{{ $ma->kdKondisiRo }}">{{ $ma->nmKondisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="s" class="col-form-label">Kondisi (S)</label>
                                                <select name="s" id="s"
                                                    class="form-control select2bs4">
                                                    <option value="">--Pilih S--</option>
                                                    @foreach ($s as $s)
                                                        <option value="{{ $s->kdKondisiRo }}">{{ $s->nmKondisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="jmlExpose" class="col-form-label">Jml Expose</label>
                                                <input type="number" name="jmlExpose"
                                                    class="form-control form-control-sm" value="1">
                                            </div>
                                            <div class="col-md-1">
                                                <label for="jmlFilmDipakai" class="col-form-label">Film
                                                    Dipakai</label>
                                                <input type="number" name="jmlFilmDipakai"
                                                    class="form-control form-control-sm" value="1">
                                            </div>
                                            <div class="col-md-1">
                                                <label for="jmlFilmRusak" class="col-form-label">Film Rusak</label>
                                                <input type="number" name="jmlFilmRusak"
                                                    class="form-control form-control-sm" value="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="kdMesin" class="col-form-label">Mesin</label>
                                                <select name="kdMesin" id="kdMesin" class="form-control select2bs4"
                                                    required>
                                                    <option value="">--Pilih Mesin--</option>
                                                    @foreach ($mesin as $mesin)
                                                        <option value="{{ $mesin->kdMesin }}">{{ $mesin->nmMesin }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Row for Catatan -->
                                        {{-- <div class="form-row mt-md-3 mt-4">
                                            <label for="catatan" class="col-form-label">Catatan</label>
                                            <div class="col-sm-5 ml-2">
                                                <textarea name="catatan" id="catatan" class="form-control" rows="1"></textarea>
                                            </div>
                                            <label class="col-form-label">Upload Foto Rontgen</label>

                                            <div class="col-sm-auto ml-2">
                                                <input type="file" name="gambar" id="fileRo"
                                                    class="form-control-sm col" placeholder=" Pilih Foto"
                                                    title="Foto Ro" />
                                            </div>
                                            <div class="col-sm-auto mt-md-0 mt-3">
                                                <input class="form-control form-control-sm col" name="Ket Foto 1"
                                                    id="ket_foto"title="Nama Foto" placeholder=" Nama Foto" />
                                            </div>
                                            <div class="form-group col-sm-auto" style="display: none;">
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    data-toggle="modal" data-target="#staticBackdrop">
                                                    Upload Lebih dari 1 Foto Rontgen
                                                </button>
                                            </div>
                                        </div> --}}

                                        <div class="form-row mt-md-3 mt-0">
                                            <div class="col-md">
                                                <label for="catatan" class="col-form-label">Catatan</label>
                                                <textarea name="catatan" id="catatan" class="form-control" rows="1"></textarea>
                                            </div>
                                            <div class="col-md rounded" style="background-color: #5ffb79a5">
                                                <label class="col-form-label">Upload Foto Rontgen</label>
                                                <div class="form-row col-sm ml-2">
                                                    <input type="file" name="gambar" id="fileRo"
                                                        class="form-control-sm col" placeholder=" Pilih Foto"
                                                        title="Foto Ro" />
                                                    {{-- </div>
                                                <div class="col-sm-auto mt-md-0 mt-3"> --}}
                                                    <input class="form-control form-control-sm col" name="Ket Foto 1"
                                                        id="ket_foto"title="Nama Foto" placeholder=" Nama Foto" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row mt-3" id="preview"style="display: none">
                                            <div class="col-12 table-responsive">
                                                <table id="tableRo"
                                                    class="table table-striped table-hover table-bordered pt-0 mt-0 fs-6"
                                                    style="width: 100%">
                                                    <thead class="bg bg-teal">
                                                        <tr>
                                                            <td class="col-2" style="width: 35px">Aksi
                                                            </td>
                                                            <td>ID</td>
                                                            <td>Foto Rontgen</td>
                                                            <td>Nama Foto</td>
                                                            <td>Tanggal</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Row for Dokter and Petugas -->
                                        <div class="form-row mt-3 d-flex justify-content-between">
                                            <div class="col-md-4">
                                                <label for="dokter" class="col-form-label">Dokter</label>
                                                <select id="dokter" class="form-control select2bs4">
                                                    <option value="">--Pilih Dokter--</option>
                                                    @foreach ($dokter as $dok)
                                                        <option value="{{ $dok->nip }}">{{ $dok->gelar_d }}
                                                            {{ $dok->nama }} {{ $dok->gelar_b }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="p_rontgen" class="col-form-label">Petugas</label>
                                                <select id="p_rontgen" class="form-control select2bs4" required>
                                                    <option value="">--Pilih Radiografer--</option>
                                                    @foreach ($radiografer as $rad)
                                                        <option value="{{ $rad->nip }}">{{ $rad->gelar_d }}
                                                            {{ $rad->nama }} {{ $rad->gelar_b }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button class="col-md-1 btn btn-success mx-2 mt-4 font-weight-bold"
                                                onclick="validateAndSubmit();">Simpan</button>
                                            <button class="col-md-2 btn btn-danger mt-4 font-weight-bold"
                                                onclick="rstForm();">Selesai /
                                                Reset</button>
                                        </div>

                                        {{-- <!-- Buttons -->
                                        <div class="form-row mt-4 justify-content-end">
                                            <button class="btn btn-success mr-2"
                                                onclick="validateAndSubmit();">Simpan</button>
                                            <button class="btn btn-danger" onclick="rstForm();">Selesai /
                                                Reset</button>
                                        </div> --}}
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Modal -->

                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Update
                                    Foto Rontgen Lainnya</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formUpdate">
                                    <label for="idFoto" class=" col-form-label font-weight-bold mb-0 ">ID Foto
                                        :</label>
                                    <div class=" input-group">
                                        <input type="number" name="idFoto" id="idFoto"
                                            class="form-control-sm col-sm" placeholder="ID Foto" />
                                    </div>
                                    <label for="nmFoto" class=" col-form-label font-weight-bold mb-0 ">Nama Foto
                                        :</label>
                                    <div class=" input-group">
                                        <input type="text" name="nmFoto" id="nmFoto"
                                            class="form-control-sm col-sm" placeholder="Nama Foto" />
                                    </div>
                                    <label for="ket_foto_new"
                                        class="Form-Control-sm col-form-label font-weight-bold mb-0 ">Ket
                                        Foto
                                        :</label>
                                    <div class=" input-group">
                                        <input type="text" name="ket_foto_new" id="ket_foto_new"
                                            class="form-control-sm col-sm" placeholder="Ket Foto" />
                                    </div>
                                    <label class="col-form-label">Pilih
                                        Foto Rontgen</label>
                                    <div class="ml-2">
                                        <input type="file" name="gambar2" id="fileRo2" class="form-control-sm"
                                            placeholder=" Pilih Foto" title="Foto Ro" /><span>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="update();">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Show Foto -->
                <div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalFotoLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalFotoLabel">Foto Rontgen </h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                {{-- <img id="modalFotoImage" src="" alt="Foto Rontgen"
                                                        class="img-fluid" style="height: 400px;width: 400px"> --}}
                                <div class="card m-2" style="cursor: pointer;">
                                    <div class="f-panzoom" id="myPanzoom">
                                        <div class="f-panzoom__viewport"style="height: 25rem;width: 25rem">
                                            <img class="f-panzoom__content" id="zoomed-image" src="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <h5 id="keteranganFoto"></h5>
                                <br>
                                <h5 id="keteranganFoto2"></h5>
                            </div>
                        </div>
                    </div>
                </div>
