                <div class="container-fluid">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="iperiksa" onclick="showPeriksa();"><b>Jenis Pemeriksaan</b></a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" id="ireagen" onclick="showReagen();"><b>Laporan Reagen</b></a>
                        </li> --}}
                    </ul>
                </div>
                <div class="container-fluid mt-1" id="periksa">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Jenis Pemeriksaan</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-layanan">Tambah Layanan</button>
                            </div>
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataPeriksa"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>Pemeriksaan</th>
                                            <th>Tarif</th>
                                            <th>Estimasi Selesai(menit)</th>
                                            <th>Satuan</th>
                                            <th>Nilai Normal</th>
                                            <th>Status</th>
                                            <th>Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="reagen">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4 class="card-title">Laporan Jumlah total pemeriksaan (penggunaan reagen)</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="table-responsive pt-2 px-2">
                                <table id="reportReagen"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Pemeriksaan</th>
                                            <th>Jumlah</th>
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
                <div class="modal fade" id="modal-layanan">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Tambah Layanan Laboratorium</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body p-2">
                                    <div class="container-fluid">
                                        <div class="card card-black">
                                            <!-- form start -->
                                            @csrf
                                            <form class="form-horizontal" id="form_layanan">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col">
                                                            <label for="nmLayanan"
                                                                class="col-sm col-form-label font-weight-bold">Nama
                                                                Layanan</label>
                                                            <div class="col-md row">
                                                                <input type="text" id="nmLayanan"
                                                                    class="form-control-sm col-md bg-white border border-white"
                                                                    placeholder="Nama Layanan">
                                                            </div>
                                                            <label for="tarif"
                                                                class="col-md col-form-label font-weight-bold">Tarif
                                                                Layanan</label>
                                                            <div class="col-md">
                                                                <input id="tarif"
                                                                    class="form-control-sm col-md bg-white border border-white"
                                                                    placeholder="Tarif" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group col">
                                                            <label for="satuan"
                                                                class="col-sm col-form-label font-weight-bold">Satuan
                                                                Hasil</label>
                                                            <div class="col-md row">
                                                                <input type="text" id="satuan"
                                                                    class="form-control-sm col-md bg-white border border-white"
                                                                    placeholder="Satuan Hasil">
                                                            </div>
                                                            <label for="estimasi"
                                                                class="col-md col-form-label font-weight-bold">Estimasi
                                                                Layanan (menit)</label>
                                                            <div class="col-md">
                                                                <input id="estimasi"
                                                                    class="form-control-sm col-md bg-white border border-white"
                                                                    placeholder="Estimasi Waktu Selesai" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group col">
                                                            <label for="layanan"
                                                                class="col-sm col-form-label font-weight-bold">Status</label>
                                                            <div class="col">
                                                                <select id="layanan"
                                                                    class="form-control select2bs4 border border-primary">
                                                                    <option value="">--Status Layanan--</option>
                                                                    <option value="1">Aktif</option>
                                                                    <option value="0">Tidak Aktif</option>
                                                                </select>
                                                            </div>
                                                            <label for="kelas"
                                                                class="col-sm col-form-label font-weight-bold">Grup</label>
                                                            <div class="col">
                                                                <select id="kelas"
                                                                    class="form-control select2bs4 border border-primary">
                                                                    <option value="">--Pilih Kelas--</option>
                                                                    <option value="9">LAYANAN LABORATORIUM</option>
                                                                    <option value="91">HEMATOLOGI</option>
                                                                    <option value="92">KIMIA DARAH</option>
                                                                    <option value="93">IMUNO SEROLOGI</option>
                                                                    <option value="94">BAKTERIOLOGI</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col">
                                                            <label for="normal"
                                                                class="col-sm col-form-label font-weight-bold">Nilai
                                                                Normal</label>
                                                            <div class="col-md row">
                                                                <textarea type="text" id="normal" class="form-control-sm col-md bg-white border border-white"
                                                                    placeholder="Nilai Normal"></textarea>
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
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    onclick="addLayanan();">Simpan</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
