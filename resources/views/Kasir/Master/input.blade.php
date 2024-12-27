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
                                <table
                                    id="dataLayanan"class="table table-striped table-hover table-bordered pt-0 mt-0 fs-6"
                                    style="width:100%" cellspacing="0">
                                    <thead class="bg bg-warning">
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>Pemeriksaan</th>
                                            <th>Tarif</th>
                                            <th>Status</th>
                                            <th>Grup</th>
                                            <th>Satuan</th>
                                            <th>Nilai Normal</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-layanan">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Form Data Layanan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body p-2">
                                    <div class="container-fluid">
                                        <!-- form start -->
                                        @csrf
                                        <form class="form-horizontal" id="FormLayanan">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-grup col-6 col-md-1">
                                                        <label for="idLayanan"
                                                            class="col-form-label font-weight-bold mb-0">ID
                                                            :</label>
                                                        <input type="text" id="idLayanan"
                                                            class="form-control form-control-sm bg-white"
                                                            placeholder="ID" readonly required />
                                                    </div>
                                                    <div class="form-grup col-6 col-md-3">
                                                        <label for="nmLayanan"
                                                            class="col-form-label font-weight-bold mb-0 ">Nama
                                                            Layanan
                                                            :</label>
                                                        <input type="text" name="nmLayanan" id="nmLayanan"
                                                            class="form-control form-control-sm"
                                                            placeholder="Nama Layanan" />
                                                    </div>
                                                    <div class="form-grup col-6 col-md-2">
                                                        <label for="layanan"
                                                            class="col-sm col-form-label font-weight-bold">Status</label>
                                                        <select id="layanan"
                                                            class="form-control select2bs4 border border-primary">
                                                            <option value="">--Status Layanan--
                                                            </option>
                                                            <option value="1">Aktif</option>
                                                            <option value="0">Tidak Aktif</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-grup col-6 col-md-2">
                                                        <label for="tarif"
                                                            class="col-md col-form-label font-weight-bold mb-0">Tarif
                                                            Layanan</label>
                                                        <input id="tarif" type="number"
                                                            class="form-control form-control-sm col-md bg-white border border-white"
                                                            placeholder="Tarif" />
                                                    </div>

                                                    <div class="form-grup col-6 col-md-4">
                                                        <label for="kelas"
                                                            class="col-sm col-form-label font-weight-bold">Grup</label>
                                                        <select id="kelas"
                                                            class="form-control select2bs4 border border-primary">
                                                            <option value="">--Pilih Kelas--</option>
                                                            @foreach ($kelas as $item)
                                                                <option value={{ $item->kelas }}>
                                                                    {{ $item->nmKelas }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-grup col-6 col-md-3">
                                                        <label for="satuan"
                                                            class="col-sm col-form-label font-weight-bold">Satuan
                                                            Hasil</label>
                                                        <input type="text" id="satuan"
                                                            class="form-control form-control-sm col-md bg-white border border-white"
                                                            placeholder="Satuan Hasil">
                                                    </div>
                                                    {{-- </div>
                                                    <div class="row"> --}}
                                                    <div class="form-group col">
                                                        <label for="normal"
                                                            class="col-sm col-form-label font-weight-bold">Nilai
                                                            Normal</label>
                                                        <div class="col-md row">
                                                            <textarea type="text" id="normal" class="form-control form-control-sm-sm col-md bg-white border border-white"
                                                                placeholder="Nilai Normal" rows="1"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-primary" onclick="validasi();">Simpan</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
