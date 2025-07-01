                <div class="container-fluid mt-1" id="tab_1">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title font-weight-bold">Daftar Pengajuan Cuti Pegawai</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="container">

                                <table class="table table-bordered table-sm" width="60%" cellspacing="0">
                                    <tbody id="tabelInfoCuti">
                                        <!-- Akan diisi lewat JS -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <!-- Input Group -->
                                <div class="col-md">
                                    <div id="dataCutiPegawai"></div>
                                    <input type="text" id="nip_cuti" name="nip_cuti" value="{{ $nip }}"
                                        hidden>
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#modal-pengajuanCuti">
                                        Formulir Pengajuan Cuti Pegawai
                                    </button>
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-form" onclick="cariDataCuti($('#nip_cuti').val())">Cari
                                        Data
                                        Cuti</button>
                                    {{-- <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="bulanCuti" readonly>
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#modal-form"
                                            onclick="cariDataCuti($('#bulanCuti').data('monthpicker'))">Cari Data
                                            Cuti</button>
                                    </div>
                                    <script>
                                        $(function() {
                                            $('#bulanCuti').datepicker({
                                                format: "yyyy-mm",
                                                startView: "months",
                                                minViewMode: "months",
                                                autoclose: true
                                            }).on('changeDate', function(e) {
                                                $(this).data('monthpicker', e.format());
                                            });
                                            $('#bulanCuti').datepicker('setDate', new Date());
                                        });
                                    </script> --}}
                                </div>

                                <!-- Accordion -->
                                <div class="col-md">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <a class="btn btn-link text-left w-100" type="button"
                                                data-toggle="collapse" id="headingOne" data-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                <strong>Klik Untuk Melihat Cara Pencarian Data</strong>
                                            </a>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <h5>Pencarian Data</h5>
                                                    <ul>
                                                        <li>Pilih tab data yang akan dicari.</li>
                                                        <li>Pilih rentang tanggal.</li>
                                                        <li>Untuk satu tanggal, klik dua kali pada tanggal tersebut.
                                                        </li>
                                                        <li>Klik tombol "Pilih" untuk mencari data.</li>
                                                        <li>Klik tombol "Cari" untuk memperbarui data.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive pt-2 px-2" id="divTabelDaftarCuti">
                                <table id="tabelDaftarCuti"
                                    class="table table-bordered table-striped dataTable no-footer dtr-inline"
                                    aria-describedby="tabelDaftarCuti_info">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Tgl Mulai</th>
                                            <th>Tgl Selesai</th>
                                            <th>Lama Cuti</th>
                                            <th>Alasan Cuti</th>
                                            <th>Keterangan</th>
                                            <th>Persetujuan</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="tab_3" style="display: none;">
                    <div class="card card-warning">
                        <div class="card-header text-light">
                            <h6 class="card-title font-weight-bold">Rekap Sisa Cuti</h6>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <!-- Input Group -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select name="tahun_cuti" id="tahun_cuti" class="form-control">
                                                <option value="">--Tahun--</option>
                                                @php
                                                    $tahun = date('Y');
                                                    for ($i = 2024; $i <= $tahun; $i++) {
                                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                                    }
                                                @endphp
                                            </select>
                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#modal-form"
                                                onclick="cariDataSisaCuti($('#tahun_cuti').val())">Perbaharui</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive pt-2 px-2" id="divTabelDaftarSisaCuti">
                                    <table class="table table-bordered table-hover dataTable dtr-inline"
                                        id="TabelSisaCuti" cellspacing="0">
                                        <thead class="bg bg-warning table-bordered">
                                            <tr>
                                                <th>Aksi</th>
                                                <th>NO</th>
                                                <th>NIP</th>
                                                <th>Nama Pegawai</th>
                                                <th>Jumlah Cuti</th>
                                                <th>Jumlah Cuti Terpakai</th>
                                                <th>Jumlah Cuti Tambahan</th>
                                                <th>Sisa Cuti</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-bordered">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mt-1" id="tab_2" style="display: none;">
                    <div class="card card-orange">
                        <div class="card-header text-light">
                            <h6 class="card-title font-weight-bold">Template Laporan Cuti ke WhatsApp</h6>
                        </div>
                        <div class="card-body shadow">
                            <div class="row">
                                <!-- Input Group -->
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="tgl_Cuti" class="col-form-label mx-3">Tanggal:</label>
                                            <input type="date" name="tgl_Cuti" id="tgl_Cuti" class="form-control"
                                                value="{{ date('Y-m-d') }}">

                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#modal-form"
                                                onclick="cariDataCutiHari()">Perbaharui</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive pt-2 px-2" id="dataCutiWa">
                                    {{-- @include('TataUsaha.Cuti.wa') --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
