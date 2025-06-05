                <div class="container-fluid">
                    <div class="card card-lime">
                        <div class="card-header">
                            <h4 class="card-title">Identitas</h4>
                        </div>
                        @csrf
                        <form class="form-horizontal" id="form_identitas">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="norm" class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                        :</label>
                                    <div class="col-sm-2 input-group" style="overflow: hidden;">
                                        <input type="text" name="norm" id="norm" class="form-control"
                                            placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required
                                            onkeyup="enterCariRM(event,'lab',this.value);" />
                                    </div>
                                    <label for="layanan" class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="layanan" class="form-control bg-white"
                                            placeholder="Layanan" readonly />
                                    </div>
                                    <label for="nama" class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                                        :</label>
                                    <div class="col-sm-4">
                                        <input type="text" id="nama" class="form-control bg-white"
                                            placeholder="Nama Pasien" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="text" id="umur" class="form-control bg-white"
                                            placeholder="umur">
                                    </div>
                                </div>
                                <div class="form-group row mt-2">
                                    <label for="tgltrans" class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="date" id="tgltrans" class="form-control bg-white"
                                            placeholder="Tanggal Transaksi" />
                                    </div>
                                    <label for="notrans" class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="notrans" class="form-control bg-white"
                                            placeholder="Nomor Transaksi" readonly required />
                                    </div>
                                    <label for="alamat" class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                        :</label>
                                    <div class="col-sm-4">
                                        <input id="alamat" class="form-control bg-white" placeholder="Alamat Pasien"
                                            readonly />
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="text" id="no_sampel"
                                            class="form-control bg-warning font-weight-bold" placeholder="No Sampel">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container-fluid" id="inputSection">
                    @csrf
                    <div class="col p-0">
                        <div class="card card-success">
                            <div class="card-header">
                                <h4 class="card-title">Input Hasil Pemeriksaan Laboratorium</h4>
                            </div>
                            <div class="card-body py-1">
                                <table id="inputHasil" class="table table-tight">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NO RM</th>
                                            <th>Pemeriksaan</th>
                                            <th>Petugas</th>
                                            <th>Hasil</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer form-row d-flex justify-content-end aligment-items-center">
                                <div class="col-3 pt-2 d-flex aligment-items-center">
                                    <div>
                                        <b class="mr-2">Waktu Selesai Hasil:</b> <span id="waktuSelesai">-</span>
                                    </div>
                                </div>
                                <div class="col-auto" id="divSwitch">
                                    <label class="switch">
                                        <input type="checkbox" id="statusSwitch">
                                        <span class="slider round"></span>
                                        <span id="statusLabel" class="status-text text-dark">Belum</span>
                                    </label>
                                </div>
                                {{-- <script>
                                    // document.getElementById('statusSwitch').addEventListener('change', function() {
                                    //     var statusLabel = document.getElementById('statusLabel');
                                    //     if (this.checked) {
                                    //         statusLabel.textContent = 'Selesai';
                                    //     } else {
                                    //         statusLabel.textContent = 'Belum Selesai';
                                    //     }
                                    // });
                                    let isCompleted = false; // Initial state: "Belum Selesai"
                                    let status = "Belum";
                                    document.getElementById('statusSwitch').addEventListener('change', function() {
                                        var statusLabel = document.getElementById('statusLabel');

                                        if (this.checked) {
                                            statusLabel.textContent = 'Selesai';
                                            status = "Selesai";
                                        } else {
                                            statusLabel.textContent = 'Belum';
                                            status = "Belum";
                                        }
                                    });
                                </script> --}}
                                <div class="col-auto">
                                    <a class="btn btn-success" id="tblSimpan" onclick="simpan();">Simpan</a>
                                </div>
                                <div class="col-auto">
                                    <a class="btn btn-danger" id="tblBatal"
                                        onclick="resetForm('Transaksi dibatalkan');">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
