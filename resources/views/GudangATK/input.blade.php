                <div class="container-fluid">
                    <div class="form-group form-row">
                        <label class="col-form-label">Rentang Tanggal :</label>
                        <div class="form-group col-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" id="reservation">
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a type="button" class="nav-link active bg-blue" id="navStok"
                                onclick="toggleSections('#tab_1');"><b>Stok
                                    ATK</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link" id="navMasuk" onclick="toggleSections('#tab_2');"><b>ATK
                                    Masuk</b></a>
                        </li>
                        <li class="nav-item">
                            <a type="button" class="nav-link" id="navMeluar" onclick="toggleSections('#tab_3');"><b>ATK
                                    Keluar</b></a>
                        </li>
                    </ul>
                </div>
                <div class="container-fluid" id="tab_1">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Stok ATK</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success mx-2">Cari Stok ATK</button>
                                <button type="button" class="btn btn-primary mx-2" data-toggle="modal"
                                    data-target="#modal_atk_baru">Tambah ATK Baru</button>
                            </div>
                            <div class="table-responsive pt-2 px-2" id="stok_atk">
                                {!! $tableStok !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="tab_2" style="display: none;">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">ATK Masuk</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-atk">Cari ATK Masuk</button>
                            </div>
                            <div class="table-responsive pt-2 px-2" id="atk_masuk">
                                {!! $tableAtkMasuk !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="tab_3" style="display: none;">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">ATK Keluar</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#modal-atk">Cari ATK Keluar</button>
                            </div>
                            <div class="table-responsive pt-2 px-2" id="atk_keluar">
                                {!! $tableAtkKeluar !!}
                            </div>
                        </div>
                    </div>
                </div>





                <!-- Modal -->
                <div class="modal fade" id="modal-atk">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Tambah ATK Masuk Dari Pembelanjaan</h4>
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
                                            <form class="form-horizontal" id="form_atk_masuk">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-group col">
                                                            <label for="nmBarang"
                                                                class="col-sm col-form-label font-weight-bold">Nama
                                                                Barang</label>
                                                            <div class="col-md row">
                                                                <input type="text" id="nmBarang"
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
                                                                    <option value="9">LAYANAN LABORATORIUM
                                                                    </option>
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
