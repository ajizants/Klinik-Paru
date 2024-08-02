                <div class="container-fluid">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#SubKelas');"><b>Sub
                                    Kelas</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link " onclick="toggleSections('#Kelas');"><b>Kelas</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link " onclick="toggleSections('#Domain'); "><b>Domain</b></a>
                        </li>
                    </ul>
                </div>
                <div class="container-fluid mt-1" id="SubKelas">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Sub Kelas Diagnosa</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-form"
                                    onclick="edit(null, 'form_subKelas','Form Tambah Sub Kelas Diagnosa')">Tambah Sub
                                    Kelas</button>
                            </div>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataSubKelas"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Domain</th>
                                            <th>Kelas</th>
                                            <th>Kode</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="Kelas" style="display: none;">
                    <div class="card card-orange">
                        <div class="card-header text-light">
                            <h4 class="card-title">Kelas Diagnosa</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-form"onclick="edit(null, 'form_kelas','Form Tambah Kelas Diagnosa')">Tambah
                                    Kelas Diagnosa</button>
                            </div>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataKelas"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Domain</th>
                                            <th>Kode</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="Domain" style="display: none;">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4 class="card-title">Domain Diagnosa</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-form"
                                    onclick="edit(null, 'form_domain','Form Tambah Domain Diagnosa')">Tambah
                                    Domain</button>
                            </div>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataDomain"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>





                <!-- Modal -->
                <div class="modal fade" id="modal-form" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    aria-labelledby="staticmodal-form" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modal-title">fsfb</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    onclick="resetForm('form_subKelas');">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body p-2">
                                    <div class="container-fluid">
                                        <div class="card card-black">
                                            <!-- form start -->
                                            @csrf
                                            <form class="form-horizontal" id="form_subKelas">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col">

                                                            <label for="kode"
                                                                class="col-sm col-form-label font-weight-bold">Kode
                                                            </label>
                                                            <div class="col-md input-group input-group-sm">
                                                                <input type="number" id="id"
                                                                    class="form-control col-md-2"
                                                                    aria-describedby="inputGroup-sizing-sm"
                                                                    placeholder="ID" readonly>
                                                                <input type="text" id="kode"
                                                                    class="form-control col-md"
                                                                    aria-describedby="inputGroup-sizing-sm"
                                                                    placeholder="Kode">
                                                            </div>

                                                            <label for="deskripsi"
                                                                class="col-sm col-form-label font-weight-bold">Deskripsi
                                                            </label>
                                                            <div class="col-md input-group input-group-sm">
                                                                <input type="text" id="deskripsi"
                                                                    class="form-control col-md"
                                                                    aria-describedby="inputGroup-sizing-sm"
                                                                    placeholder="Deskripsi">
                                                            </div>

                                                            <label for="kelas"
                                                                class="col-md col-form-label font-weight-bold"
                                                                id="kelasLabel">Kelas
                                                            </label>
                                                            <div class="col-md" id="kelasDiv">
                                                                <select id="kelas" class="form-control select2bs4"
                                                                    placeholder="Kelas">
                                                                    <option value="">-- Pilih Kelas --</option>
                                                                    @foreach ($kelas as $kelas)
                                                                        <option value="{{ $kelas->kode }}">
                                                                            {{ $kelas->kelas }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <label for="domain"
                                                                class="col-md col-form-label font-weight-bold"
                                                                id="domainLabel">Domain
                                                            </label>
                                                            <div class="col-md" id="domainDiv">
                                                                <select id="domain" class="form-control select2bs4"
                                                                    placeholder="Domain">
                                                                    <option value="">-- Pilih Domain --</option>
                                                                    @foreach ($domain as $domain)
                                                                        <option value="{{ $domain->kode }}">
                                                                            {{ $domain->domain }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end" id="simpanSubKelas">
                                <button type="button" class="btn btn-primary"
                                    onclick="validasi('Sub Kelas');">Simpan</button>
                            </div>
                            <div class="modal-footer justify-content-end" id="simpanKelas" style="display: none;">
                                <button type="button" class="btn btn-primary"
                                    onclick="validasi('Kelas');">Simpan</button>
                            </div>
                            <div class="modal-footer justify-content-end" id="simpanDomain" style="display: none;">
                                <button type="button" class="btn btn-primary"
                                    onclick="validasi('Domain');">Simpan</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
