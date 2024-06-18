                <div class="container-fluid">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a type="button" class="nav-link active bg-blue" id="iperiksa"
                                onclick="showPeriksa();"><b>Jenis
                                    Pemeriksaan</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link" id="iUkuran" onclick="showUkuran();"><b>Ukuran
                                    Film</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link" id="ikondisi" onclick="showKondisi();"><b>Kondisi
                                    Pemotretan</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link" id="iproyeksi" onclick="showProyeksi();"><b>Proyeksi
                                    Pemotretan</b></a>
                        </li>
                    </ul>
                </div>
                <div class="container-fluid mt-1" id="periksa">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Jenis Pemeriksaan</h4>
                        </div>
                        <div class="card-body shadow">
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#editFotoModal">
                                Tambah Data Pemeriksaan
                            </button>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataPeriksa"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>Pemeriksaan</th>
                                            <th>Tarif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tbody>

                                    </tbody>
                                </table>
                                </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="ukuran">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4 class="card-title">Ukuran Film</h4>
                        </div>
                        <div class="card-body shadow">
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#editUkuranModal">
                                Tambah Data Ukuran Film
                            </button>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataUkuran"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>Ukuran Film</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="kondisi">
                    <div class="card card-info">
                        <div class="card-header">
                            <h4 class="card-title">Kondisi Pemotretan</h4>
                        </div>
                        <div class="card-body shadow">
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#editKondisiModal">
                                Tambah Data Ukuran Film
                            </button>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataKondisi"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>Kondisi Pemotretan</th>
                                            <th>Grup Kondisi</th>
                                            <th>Status (1 = aktif, 0 = non-aktif)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="proyeksi">
                    <div class="card card-success">
                        <div class="card-header">
                            <h4 class="card-title">Proyeksi Pemotretan</h4>
                        </div>
                        <div class="card-body shadow">
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#editProyeksiModal">
                                Tambah Data Proyeksi
                            </button>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataProyeksi"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>Proyeksi Pemotretan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Modal Edit Foto -->
                <div class="modal fade " id="editFotoModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    aria-labelledby="editFotoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="editFotoModalLabel">Edit Jenis Pemeriksaan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formFoto">
                                    <div class="form-group row">
                                        <label for="kdFoto" class="col-sm-3 col-form-label">Kode</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="kdFoto"
                                                placeholder="Kode" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nmFoto" class="col-sm-3 col-form-label">Nama Pemeriksaan</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="nmFoto"
                                                placeholder="Nama Pemeriksaan">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tarif" class="col-sm-3 col-form-label">Tarif
                                            Pemeriksaan</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="tarif"
                                                placeholder="Tarif Pemeriksaan">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="simpanFoto();">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Edit Proyeksi -->
                <div class="modal fade " id="editProyeksiModal" data-backdrop="static" data-keyboard="false"
                    tabindex="-1" aria-labelledby="editProyeksiModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="editProyeksiModalLabel">Edit Jenis Proyeksi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formProyeksi">
                                    <div class="form-group row">
                                        <label for="kdProyeksi" class="col-sm-3 col-form-label">Kode</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="kdProyeksi"
                                                placeholder="Kode Proyeksi" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nmProyeksi" class="col-sm-3 col-form-label">Nama
                                            Proyeksi</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="nmProyeksi"
                                                placeholder="Nama Proyeksi">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="simpanProyeksi();">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Kondisi -->
                <div class="modal fade " id="editKondisiModal" data-backdrop="static" data-keyboard="false"
                    tabindex="-1" aria-labelledby="editKondisiModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="editKondisiModalLabel">Edit Jenis Kondisi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formKondisi">
                                    <div class="form-group row">
                                        <label for="kdKondisi" class="col-sm-3 col-form-label">Kode</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="kdKondisi"
                                                placeholder="Kode" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nmKondisi" class="col-sm-3 col-form-label">Nama
                                            Kondisi</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="nmKondisi"
                                                placeholder="Nama Kondisi">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="grup" class="col-sm-3 col-form-label">Grup
                                            Kondisi</label>
                                        <div class="col-sm">
                                            <select id="grup" name="grup"
                                                class="form-control select2bs4 petugas" required="">
                                                <option value="">--Pilih Grup--</option>
                                                <option value="KV">KV</option>
                                                <option value="mA">mA</option>
                                                <option value="s">s</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status" class="col-sm-3 col-form-label">Status
                                            Kondisi</label>
                                        <div class="col-sm">
                                            <select id="status" name="status"
                                                class="form-control select2bs4 petugas" required="">
                                                <option value="">--Pilih Status--</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="simpanKondisi();">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Edit Ukuran -->
                <div class="modal fade " id="editUkuranModal" data-backdrop="static" data-keyboard="false"
                    tabindex="-1" aria-labelledby="editUkuranModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="editUkuranModalLabel">Edit Jenis Ukuran</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formUkuran">
                                    <div class="form-group row">
                                        <label for="kdFilm" class="col-sm-3 col-form-label">Kode</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="kdFilm"
                                                placeholder="Kode" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="ukuranFilm" class="col-sm-3 col-form-label">Ukuran</label>
                                        <div class="col-sm">
                                            <input type="text" class="form-control" id="ukuranFilm"
                                                placeholder="Nama Ukuran">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="simpanFilm();">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
