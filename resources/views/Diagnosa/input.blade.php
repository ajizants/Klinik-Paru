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
                                                        class="form-control form-control-sm bg-white"
                                                        placeholder="Kd ICD X" />
                                                </div>

                                                <div class="col-sm">
                                                    <label class="col-form-label" for="masuk">Diagnosa
                                                        :</label>
                                                    <input type="text" id="diagnosa" name="diagnosa"
                                                        class="form-control form-control-sm bg-white"
                                                        placeholder="Kd ICD X" hidden />
                                                    <select name="icdx" id="icdx" class="form-control select2">
                                                    </select>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $('#icdx').select2({
                                                                placeholder: 'Cari Diagnosa...',
                                                                ajax: {
                                                                    url: '/api/diagnosa_icd_x', // Endpoint untuk mengambil data diagnosa
                                                                    dataType: 'json',
                                                                    delay: 250, // Tambahkan jeda saat mengetik
                                                                    data: function(params) {
                                                                        return {
                                                                            search: params.term, // Kata kunci pencarian
                                                                            limit: 20, // Batasi jumlah hasil
                                                                        };
                                                                    },
                                                                    processResults: function(data) {
                                                                        return {
                                                                            results: data.map(function(item) {
                                                                                return {
                                                                                    id: item.kdDx,
                                                                                    text: item.kdDx + ' - ' + item.diagnosa,
                                                                                };
                                                                            }),
                                                                        };
                                                                    },
                                                                    cache: true,
                                                                },
                                                            });

                                                            // Isi kode ICD X ke input kdDx saat item dipilih
                                                            $('#icdx').on('select2:select', function(e) {
                                                                const data = e.params.data;
                                                                $('#kdDx').val(data.id);
                                                                $('#diagnosa').val(data.text);
                                                            });
                                                        });
                                                    </script>

                                                </div>
                                                <div class="col-sm">
                                                    <label class="col-form-label" for="mapping">Mapping :</label>
                                                    <input type="text" id="mapping"
                                                        class="form-control form-control-sm bg-white"
                                                        placeholder="Maaping Diagnosa" />
                                                </div>
                                            </div>

                                            <div class="form-group row d-flex justify-content-end">
                                                <div class="col-sm-2 ">
                                                    {{-- <label class="col-form-label" for="">Aksi</label> --}}
                                                    <div class="d-flex justify-content-start">
                                                        <a type="button" id="btnSimpan" class="mx-2 btn  btn-primary"
                                                            onclick="simpanDX(true);">Simpan</a>
                                                        <a type="button" id="btnUpdate" class="mx-2 btn  btn-warning"
                                                            onclick="simpanDX(false);" style="display: none;">Update</a>
                                                        <a type="button" id="btnBatal" class="mx-2 btn  btn-secondary"
                                                            onclick="resetForm();">Batal</a>
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
                                                            <th class="col-1 text-center">Aksi</th>
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
