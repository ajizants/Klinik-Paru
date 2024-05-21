                {{-- input --}}
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
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Formulir Pencarian</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    @csrf
                                    <form class="form-horizontal">
                                        <div class="card-body" id="frm-pencarian">
                                            <div class="form-grup row">
                                                <label for="norm"
                                                    class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                                    :</label>
                                                <div class="col-sm-2 input-group">
                                                    <input type="text" name="norm" id="norm"
                                                        class="form-control" placeholder="No RM" maxlength="6"
                                                        pattern="[0-9]{6}" />
                                                </div>

                                                <label class="col-sm-1 col-form-label">Tanggal:</label>
                                                <div class="input-group col-sm-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control float-right"
                                                        id="reservation">
                                                </div>

                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </form>
                                </div>
                            </div>
                            @csrf
                            <form class="">
                                <div class="container-fluid" id="formtrans">
                                    <div class="form-group">
                                        <div class="card card-success">
                                            <div class="card-header">
                                                <h3 class="card-title">Hasil Pemotretan</h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <table id="hasilRo" class="display" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Tanggal</th>
                                                            <th>No RM</th>
                                                            <th>Nama</th>
                                                            <th>Aksi</th>
                                                            <!-- Tambahkan kolom sesuai dengan data yang diterima dari permintaan AJAX -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Data akan dimasukkan di sini setelah permintaan AJAX berhasil -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body-->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
