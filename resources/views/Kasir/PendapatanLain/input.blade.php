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
                                @csrf
                                <form class="form-group " id="form_input">
                                    <div class="form-row">
                                        <div class="form-group col mx-2">
                                            <div class="form-group row">
                                                <label class="col-form-label col-2" for="tanggal">Tanggal :</label>
                                                <div class="col-sm">
                                                    <input type="date" id="tanggal" class="form-control bg-white"
                                                        placeholder="Tanggal" />
                                                </div>
                                                <div class="col-2">
                                                    <input type="number" id="id" class="form-control bg-white"
                                                        placeholder="ID" readonly />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-2" for="jumlah">Jumlah :</label>
                                                <div class="col-sm">
                                                    <input type="text" id="jumlah" class="form-control bg-white"
                                                        placeholder="Jumlah" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-2" for="penyetor">Petugas :</label>
                                                <div class="col-sm">
                                                    <select id="penyetor" class="form-control select2">
                                                        <option value="Nasirin">Nasirin</option>
                                                        <option value="Desiana Budi P.">Desiana Budi P.
                                                        </option>
                                                        <option value="Popy P.">Popy P.</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col mx-2">
                                            <div class="form-group row">
                                                <label class="col-form-label col-2"
                                                    for="asal_pendapatan">Keterangan:</label>
                                                <div class="col-sm">
                                                    <input type="text" id="asal_pendapatan"
                                                        class="form-control bg-white" placeholder="Keterangan" />
                                                </div>
                                            </div>
                                            <div class="form-group form-row">
                                                <div class="col-sm-2">
                                                    <strong>Penulisan Ket:</strong>
                                                </div>
                                                <div class="col-sm">
                                                    <ul>
                                                        <li>Pendapatan dari BPJS tuliskan "BPJS"</li>
                                                        <li>Saldo, tuliskan "SALDO"</li>
                                                        <li>Yang lain bebas</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row ">
                                        <div class="col d-flex justify-content-end">
                                            <a type="button" id="btnSimpan" class="mx-2 btn  btn-primary"
                                                onclick="simpanPendLain();">Simpan</a>
                                            <a type="button" id="btnBatal" class="mx-2 btn  btn-secondary"
                                                onclick="resetForm();">Batal</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="container-fluid" id="formLayanan">
                                <div class="p-0 ml-2 card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Data
                                            Transaksi Pendapatan/Pengeluaran Lain</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-4">
                                        <div class="form-group row">
                                            <label for="tahun" class="col-form-label col-1">Tahun :</label>
                                            <div class="col-2">
                                                <select name="Tahun" id="tahun" class="form-control">
                                                    @foreach ($listYear as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button class="btn btn-success" onclick="getDataPendLain()">Cari
                                                Data</button>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="dataPendapatanLain" name="dataPendapatanLain"
                                                class="table table-striped table-tight table-hover" style="width:100%"
                                                cellspacing="0">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th>Aksi</th>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Jumlah</th>
                                                        <th>Keterangan</th>
                                                        <th>Petugas</th>
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
