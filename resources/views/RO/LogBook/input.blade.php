                {{-- input --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Laporan Radiologi/Log
                            Book</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2">
                            <div class="container-fluid py-2">
                                <div class="form-group row ">
                                    <label for="reservation" class="col-sm-1 col-form-label">Tanggal:</label>
                                    <div class="input-group col-sm-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="reservation">
                                    </div>
                                    <div class="mx-2">
                                        <button type="button" class="btn btn-primary"
                                            onclick="cariRo(tglAwal, tglAkhir)">Cari Data Log Book</button>
                                    </div>
                                    <div class="mx-2">
                                        <button type="button" class="btn btn-success"
                                            onclick="cariKegiatanRo(tglAwal, tglAkhir)">Cetak Data Kegiatan</button>
                                    </div>
                                    <div class="mx-2">
                                        <button type="button" class="btn btn-warning"
                                            onclick="cariRo(tglAwal, tglAkhir,'cetak')">Cetak Data Log Book</button>
                                    </div>
                                    <div class="mx-2">
                                        <select name="petugas" id="petugas" class="form-control">
                                            <option value="">-- Pilih Petugas --</option>
                                            @foreach ($radiografer as $rad)
                                                <option value="{{ $rad->nip }}">{{ $rad->gelar_d }}
                                                    {{ $rad->nama }} {{ $rad->gelar_b }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Jumlah Petugas</h3>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table table-bordered" id="jumlahPetugas">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Hasil Pemotretan</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body table-responsive" id="containerTableLogBook">
                                        <table id="hasilRo" class="display table table-striped table-hover">
                                            <thead class="bg bg-secondary">
                                                <tr>
                                                    <th>No</th>
                                                    <th>No Reg</th>
                                                    <th>Tanggal</th>
                                                    <th>No RM</th>
                                                    <th>Nama</th>
                                                    <th>Jaminan</th>
                                                    <th>JK</th>
                                                    <th class="col-4">Alamat</th>
                                                    <th>Nama Foto</th>
                                                    <th>Ukuran Film</th>
                                                    <th>Kondisi</th>
                                                    <th>Jml Film</th>
                                                    <th>Jml Expose</th>
                                                    <th>Jml Rusak</th>
                                                    <th>Proyeksi</th>
                                                    <th>Mesin</th>
                                                    <th>Catatan</th>
                                                    <th>Petugas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot class="bg bg-secondary">
                                                <tr>
                                                    <th>Totals</th>
                                                    <th colspan="7"></th>
                                                    <th id="jenisFoto"></th>
                                                    <th colspan="2"></th>
                                                    <th id="totalJmlFilmDipakai"></th>
                                                    <th id="totalJmlExpose"></th>
                                                    <th id="totalJmlFilmRusak"></th>
                                                    <th id="proyeksi"></th>
                                                    <th id ="mesin"> </th>
                                                    <th> </th>
                                                    <th id ="petugas"> </th>
                                                </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
