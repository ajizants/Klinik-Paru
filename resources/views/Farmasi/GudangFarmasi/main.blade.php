{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('Farmasi.GudangFarmasi.inventaris')



    <!-- Form Tambah Obat Modal-->
    <div class="modal fade" id="formAddJenisObat" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Tambah Jenis Obat</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-group">
                        <div class="form-group row">
                            <div class="form-inline col">
                                <label for="nmObat" class="col-4 d-flex justify-content-start"> Nama Obat </label>
                                <input type="text" id="nmObat" class="form-control-sm col-5 border border-info"
                                    placeholder="Nama Obat" required>
                            </div>
                            <div class="form-inline col">
                                <label for="sumberObat" class="col-4 d-flex justify-content-start"> Sumber Obat </label>
                                <select id="sumberObat" class="form-control select2bs4 border border-info"
                                    style="width: 14rem; height:">
                                    <option value="">--Sumber Obat--</option>
                                    <option value="1">Gudang Farmasi</option>
                                    <option value="2">BLUD</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <div class="form-inline col">
                                <label for="tglBeli" class="col-4 d-flex justify-content-start"> Tanggal Pembelian </label>
                                <input type="date" id="tglBeli" class="form-control-sm col-5 border border-info"
                                    value="{{ now()->format('Y-m-d') }}"required>
                            </div>
                            <div class="form-inline col">
                                <label for="tglED" class="col-4 d-flex justify-content-start"> Tanggal ED </label>
                                <input type="date" id="tglED" class="form-control-sm col-5 border border-info"
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <div class="form-inline col">
                                <label for="hargaBeli" class="col-4 d-flex justify-content-start"> Harga Beli </label>
                                <input type="text" id="hargaBeli" class="form-control-sm col-5 border border-info"
                                    placeholder="Harga Beli" required>
                            </div>
                            <div class="form-inline col">
                                <label for="hargaJual" class="col-4 d-flex justify-content-start"> Harga Jual </label>
                                <input type="text" id="hargaJual" class="form-control-sm col-5 border border-info"
                                    placeholder="Harga Jual" required>
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <div class="form-inline col">
                                <label for="jenis" class="col-4 d-flex justify-content-start"> Jenis </label>
                                <select id="jenis" class="form-control select2bs4 border border-black"
                                    style="width: 14rem; height:">
                                    <option value="">--Pilih Jenis--</option>
                                    <option value="1">Obat</option>
                                    <option value="2">Bahan Medis Habis Pakai/BMHP</option>
                                </select>
                            </div>
                            <div class="form-inline col">
                                <label for="stokAwal" class="col-4 d-flex justify-content-start"> Stock Awal </label>
                                <input type="text" id="stokAwal" class="form-control-sm col-5 border border-info"
                                    placeholder="Stock Awal" required>
                            </div>
                        </div>
                        <div class="form-group col-auto pt-3">
                            <span><a id="addJenisTindakan" class="btn btn-success d-flex justify-content-center mx-2">+
                                    Tambah Obat Baru</a>
                            </span>
                        </div>
                    </form>
                    <div class="border border-black">
                        <div class="card-body card-body-hidden p-2">
                            <div
                                class=" d-flex justify-content-center z-3 position-sticky w-100 border-bottom-primary mb-3">
                                <h5 class=""><b> Gudang Farmasi</b></h5>
                            </div>
                            <table id="gudangObat" class="table table-striped fs-6" style="width:100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="20px">Aksi</th>
                                        <th width="20px"class="text-center">No</th>
                                        <th width="40px" class="text-center">Nama Barang</th>
                                        <th width="36px"class="text-center">Stok Awal</th>
                                        <th width="36px"class="text-center">Stok Akhir</th>
                                        <th width="36px"class="text-center">Masuk</th>
                                        <th width="36px"class="text-center">keluar</th>
                                        <th width="40px" class="text-center">Tgl ED</th>
                                    </tr>
                                </thead>
                            </table>
                            <div id="loadingSpinner" style="display: none;" class="text-center">
                                <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Selesai</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


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
    <script defer src="{{ asset('js/gudangFarmasi.js') }}"></script>
    <script defer src="{{ asset('js/populate.js') }}"></script>





    <div class="modal fade" id="modal-Obat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-primary">
                <div class="modal-header">
                    <h4 class="modal-title">Primary Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Horizontal Form -->
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col">
                                    <input type="text" class="form-control" id="namaObatBasic"
                                        placeholder="Nama Obat">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light btn-danger text-dark" data-dismiss="modal"
                        id="klos">Close</button>
                    <button type="button" class="btn btn-outline-light btn-success text-dark"
                        id="simpanBasicObat">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Stok Obat Gudang Kurang Dari 200</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class=" border border-black">
                                <div class="card-body card-body-hidden p-2">
                                    <table id="limitStokGudang" class="table table-striped fs-6" style="width:100%"
                                        cellspacing="0">
                                        <thead class="table-secondary table-sm">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Barang</th>
                                                <th>Pabrikan</th>
                                                <th>Sediaan</th>
                                                <th>Suplier</th>
                                                <th>Tgl ED</th>
                                                <th>Stok Akhir</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div id="loadingSpinner" style="display: none;" class="text-center">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
