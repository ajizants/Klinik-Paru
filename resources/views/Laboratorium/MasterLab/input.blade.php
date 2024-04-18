                <div class="container-fluid">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="iperiksa" onclick="showPeriksa();"><b>Jenis Pemeriksaan</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ireagen" onclick="showReagen();"><b>Laporan Reagen</b></a>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">
                                Launch static backdrop modal
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="container-fluid mt-1" id="periksa">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Jenis Pemeriksaan</h4>
                        </div>
                        <div class="card-body shadow">
                            <div class="table-responsive pt-2 px-2">
                                <table id="dataPeriksa"class="table table-striped pt-0 mt-0 fs-6" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Aksi</th>
                                            <th>No</th>
                                            <th>Pemeriksaan</th>
                                            <th>Tarif</th>
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
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Understood</button>
                            </div>
                        </div>
                    </div>
                </div>
