                {{-- input tindakan --}}
                <div class="card card-primary shadow mb-4">
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-header bg-primary">
                            <h4 class="font-weight-bold">Diagnosa Mapping</h4>
                        </div>
                        <div class="card-body p-2">
                            <div class="container-fluid">
                                @csrf
                                <form class="form-group " id="form_input">
                                    <div class="form-row">
                                        <div class="form-group col mx-2">
                                            <div class="form-group row">
                                                <div class="col-sm-1">
                                                    <label class="col-form-label" for="kdDx">ICD X :</label>
                                                    <input type="text" id="kdDx" name="kdDx"
                                                        class="form-control bg-white" placeholder="Kd ICD X" />
                                                </div>

                                                <div class="col-sm">
                                                    <label class="col-form-label" for="masuk">Diagnosa
                                                        :</label>
                                                    <input type="text" id="diagnosa" name="diagnosa"
                                                        class="form-control bg-white"
                                                        placeholder="Nama Diagnosa ICD X" />
                                                </div>
                                                <div class="col-sm">
                                                    <label class="col-form-label" for="mapping">Mapping :</label>
                                                    <input type="text" id="mapping" class="form-control bg-white"
                                                        placeholder="Maaping Diagnosa" />
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="col-form-label" for="">Aksi</label>
                                                    <div class="d-flex justify-content-start">
                                                        <a type="button" id="btnSimpan" class="mx-2 btn  btn-primary"
                                                            onclick="simpanPendLain();">Simpan</a>
                                                        <a type="button" id="btnBatal" class="mx-2 btn  btn-secondary"
                                                            onclick="resetForm('lainnya');">Batal</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                <div class="container-fluid" id="formLayanan">
                                    <div class="p-0 ml-2 card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Data
                                                Transaksi Pendapatan/Pengeluaran Lain</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-4">
                                            <button class="btn btn-success" onclick="getData()">Cari
                                                Data Diagnosa</button>
                                            <div class="table-responsive">
                                                <table id="dataDx" name="dataDx"
                                                    class="table table-striped table-tight table-hover"
                                                    style="width:100%" cellspacing="0">
                                                    <thead class="bg-secondary">
                                                        <tr>
                                                            <th>Aksi</th>
                                                            <th>No</th>
                                                            <th>ICD X</th>
                                                            <th>Diagnosa</th>
                                                            <th>Mapping</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
