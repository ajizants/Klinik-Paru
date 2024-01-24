@extends('Template.lte')

@section('content')
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" onclick="showtelaah()"><b>Kajian Resep</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showriwayat()"><b>Riwayat</b></a>
        </li>
        <div class="input-group col d-flex justify-content-start ml-5">
            <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal" value="{{ old('date') }}"
                required>
            <div class="input-group-addon btn btn-danger">
                <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top" title="Update Pasien Hari ini"
                    id="cariantrian"></span>
            </div>
        </div>
    </ul>


    <div class="container-fluid" id="telaah">
        <div class="card card-lime">
            <div class="card-header">
                <h4 class="card-title">Kajian Resep</h4>
            </div>
            <div class="card-body">
                {{-- <div class="table-responsive pt-2 px-2"> --}}
                <table id="telaah" name="telaah" class="table table-bordered" style="border-width: 3px;">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Alamat</th>
                            <th rowspan="2">Waktu R/ Masuk</th>
                            <th colspan="5">Telaah resep</th>
                            <th colspan="6">Pemberian Informasi Obat</th>
                            <th colspan="2">Sesuai Formularium</th>
                            <th rowspan="2"class="rotate text-nowrap">Rekonsiliasi</th>
                            <th rowspan="2"class="rotate text-nowrap">Konseling</th>
                            <th rowspan="2">Waktu Selesai</th>
                            <th rowspan="2">Petugas</th>
                        </tr>
                        <tr>
                            <th class="rotate text-nowrap">Pasien</th>
                            <th class="rotate text-nowrap">Obat</th>
                            <th class="rotate text-nowrap">Dosis</th>
                            <th class="rotate text-nowrap">Cara Pemberian</th>
                            <th class="rotate text-nowrap">Informasi</th>
                            <th class="rotate text-nowrap">Nama Obat</th>
                            <th class="rotate text-nowrap">Dosis</th>
                            <th class="rotate text-nowrap">Indikasi</th>
                            <th class="rotate text-nowrap">Cara Pakai</th>
                            <th class="rotate text-nowrap">Cara Simpan</th>
                            <th class="rotate text-nowrap">Efek Samping</th>
                            <th class="rotate text-nowrap">Selesai</th>
                            <th class="rotate text-nowrap">Tidak Selesai</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
                {{-- </div> --}}
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
    <div class="container-fluid" id="riwayat">
        <div class="container-fluid">
            <div class="card card-lime">
                <div class="card-header">
                    <h4 class="card-title">Identitas</h4>
                </div>
                @csrf
                <form class="form-horizontal">
                    <div class="card-body" id="inputSection">
                        <div class="form-grup row">
                            <label for="norm" class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                :</label>
                            <div class="col-sm-2 input-group" style="overflow: hidden;">
                                <input type="text" name="norm" id="norm" class="form-control" placeholder="No RM"
                                    maxlength="6" pattern="[0-9]{6}" required />
                                <div class="input-group-addon btn btn-danger">
                                    <span class="fa-solid fa-magnifying-glass" onclick="searchRMObat();"
                                        data-toggle="tooltip" data-placement="top" title="Selain Pasien Hari ini"></span>
                                </div>
                                <marquee id="ket" class="col-sm-12 text-danger font-weight-bold" direction="left"
                                    behavior="scroll" scrollamount="5">
                                    Klik Tombol Cari berwarna merah untuk menginputkan obat non pasien
                                </marquee>
                            </div>


                            <label for="layanan" class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                                :</label>
                            <div class="col-sm-2">
                                <input type="text" id="layanan" class="form-control bg-white" placeholder="Layanan"
                                    readonly />
                            </div>
                            <label for="nama" class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                                :</label>
                            <div class="col-sm-5">
                                <input type="text" id="nama" class="form-control bg-white" placeholder="Nama Pasien"
                                    readonly>
                            </div>
                        </div>
                        <div class="form-grup row mt-2">
                            <label for="tgltind" class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                :</label>
                            <div class="col-sm-2">
                                <input type="text" id="tgltind" class="form-control bg-white" placeholder="Tanggal"
                                    readonly hidden />
                                <input type="text" id="tgltrans" class="form-control bg-white"
                                    placeholder="tgltrans" readonly />
                            </div>
                            <label for="notrans" class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                :</label>
                            <div class="col-sm-2">
                                <input type="text" id="notrans" class="form-control bg-white"
                                    placeholder="Nomor Transaksi" readonly />
                            </div>
                            <label for="alamat" class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                :</label>
                            <div class="col-sm-5">
                                <input id="alamat" class="form-control bg-white" placeholder="Alamat Pasien"
                                    readonly />
                            </div>
                        </div>
                        <div class="mt-2 form-grup row d-flex justify-content-center">
                            <label for="apoteker" class="col-sm-1 col-form-label font-weight-bold">Petugas
                                :</label>
                            <div class="col-sm-4">
                                <select id="apoteker" class="form-control select2bs4 border border-primary">
                                    <option value="">--Pilih Petugas--</option>
                                </select>
                            </div>
                            <label for="dokter" class="col-sm-1 col-form-label font-weight-bold">Dokter
                                :</label>
                            <div class="col-sm-4">
                                <select id="dokter" class="form-control select2bs4 mb-3 border border-primary">
                                    <option value="">--Pilih Dokter--</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 form-grup d-flex justify-content-center">
                            <button type="button" class="btn btn-primary col" data-toggle="modal"
                                data-target="#riwayatModal" onclick="showRiwayat()">Lihat
                                Riwayat
                                Transaksi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="container-fluid row mx-0">
            <div class="card card-warning col p-0 ">
                <div class="card-header">
                    <h4 class="card-title">Data
                        Transaksi Obat Farmasi</h4>
                </div>
                <div class="table-responsive pt-2 px-2">
                    <table id="dataFarmasi" name="dataFarmasi" class="table table-striped" style="width:100%"
                        cellspacing="0">
                        <thead class="bg-secondary">
                            <tr>
                                <th class="no-total" width="35px">Aksi</th>
                                <th class="col-1 text-center">No</th>
                                <th class="col-1">RM</th>
                                <th class="col-4">Obat</th>
                                <th class="">Qty</th>
                                <th class="no-total">Total</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="loadingSpinner" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                </div>
            </div>
            <div class="card card-lime col p-0">
                <div class="card-header">
                    <h4 class="card-title">Data
                        Transaksi Obat & BMHP IGD</h4>
                </div>
                <div class="table-responsive pt-2 px-2">
                    <table id="dataIGD" name="dataIGD" class="table table-striped" style="width:100%"
                        cellspacing="0">
                        <thead class="bg-fuchsia">
                            <tr>
                                <th class="no-total" width="35px">Aksi</th>
                                <th class="col-1 text-center">No</th>
                                <th class="col-1">RM</th>
                                <th class="col-3">Obat</th>
                                <th class="">Qty</th>
                                <th class="no-total">Total</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="loadingSpinner" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="form-row d-flex justify-content-end">
                <div class="col-md-2 d-flex justify-content-end d-flex align-items-center">
                    <label for="tagihan" class="form-label mb-0"><b>Total
                            Tagihan :</b></label>
                </div>
                <div class="col-md-2">
                    <input type="text"id="tagihan" class="form-control border border-info" placeholder="Total Tagihan"
                        readonly>
                </div>
                <div class="col-auto">
                    <a class="btn btn-danger" id="tblBatal">Batal</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-success" id="tblSimpan">Selesai</a>
                </div>
            </div>
        </div>
    </div>

    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>
    @include('Template.script')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        function dataFarmasi() {
            var norm = $("#norm").val();
            var tgl = $("#norm").val();

            if ($.fn.DataTable.isDataTable("#dataFarmasi")) {
                var table = $("#dataFarmasi").DataTable();
                table.destroy();
            }

            $.ajax({
                url: "/api/transaksiFarmasi",
                type: "POST",
                data: {
                    notrans: notrans
                },
                success: function(response) {
                    response.forEach(function(item, index) {
                        item.actions = `<a href="" class="edit"
                                    data-id="${item.idAptk}"
                                    data-norm="${item.norm}"
                                    data-idObat="${item.idObat}"
                                    data-product_id="${item.product_id}"
                                    data-obat="${item.nmObat}"
                                    data-qty="${item.qty}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    data-id="${item.idAptk}"
                                    data-norm="${item.norm}"
                                    data-idObat="${item.idObat}"
                                    data-obat="${item.nmObat}"
                                    data-qty="${item.qty}"><i class="fas fa-trash"></i></a>`;
                        item.no = index + 1;
                        item.total = `${item.total.toLocaleString()}`;
                    });

                    $("#dataFarmasi").DataTable({
                        data: response,
                        columns: [{
                                data: "actions",
                                className: "px-0 col-1 text-center"
                            },
                            {
                                data: "no",
                                className: "col-1 text-center"
                            },
                            {
                                data: "norm",
                                className: "col-1 "
                            },
                            {
                                data: "nmObat"
                            },
                            {
                                data: "qty",
                                className: "col-1"
                            },
                            {
                                data: "total",
                                className: "totalHarga"
                            },
                        ],
                        order: [2, "asc"],
                    });
                    tagihan();
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }

        function dataBMHP() {
            var notrans = $("#notrans").val();
            if ($.fn.DataTable.isDataTable("#dataIGD")) {
                var tabletindakan = $("#dataIGD").DataTable();
                tabletindakan.clear().destroy();
            }

            $.ajax({
                url: "/api/cariTotalBmhp",
                type: "post",
                data: {
                    notrans: notrans
                },
                success: function(response) {
                    response.forEach(function(item, index) {
                        item.actions = `<a href="" class="delete"
                                    data-id="${item.id}"
                                    data-idTind="${item.idTind}"
                                    data-kdtind="${item.kdTind}"
                                    data-tindakan="${item.tindakan}"
                                    data-kdBmhp="${item.kdBmhp}"
                                    data-jumlah="${item.jumlah}">
                                    <i class="fas fa-trash"></i></a>`;
                        item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                    });

                    $("#dataIGD").DataTable({
                        data: response,
                        columns: [{
                                data: "actions",
                                className: "text-center"
                            },
                            {
                                data: "no"
                            },
                            {
                                data: "tindakan.norm"
                            },
                            {
                                data: "bmhp.nmObat"
                            },
                            {
                                data: "jml"
                            },
                            {
                                data: "biaya",
                                className: "totalHarga"
                            },
                            // { data: "tindakan.petugas_pegawai.nama" },
                            // { data: "tindakan.dokter_pegawai.nama" },
                        ],
                        order: [2, "asc"],
                        paging: true,
                        pageLength: 5,
                    });
                    tagihan();
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }
        let telaah = document.getElementById("telaah");
        let riwayat = document.getElementById("riwayat");


        function showriwayat() {
            telaah.style.display = 'none'; // Menyembunyikan elemen dengan ID "telaah"
            riwayat.style.display = 'block'; // Menampilkan elemen dengan ID "riwayat"
        }

        function showtelaah() {
            telaah.style.display = 'block'; // Menyembunyikan elemen dengan ID "telaah"
            riwayat.style.display = 'none'; // Menampilkan elemen dengan ID "riwayat"
        }

        $(document).ready(function() {
            showtelaah()
        })
    </script>
@endsection
