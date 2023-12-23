<ul class="nav nav-tabs py-2">
    <li class="nav-item ml-1">
        <a class="nav-link bg-warning active bg-white" id="iStok"><b>Stock Dari Gudang</b></a>
    </li>
    <li class="nav-item ml-1">
        <a class="nav-link bg-lime" id="iBmhp"><b>Stock BMHP IGD</b></a>
    </li>
    <li class="nav-item ml-1">
        <a class="nav-link bg-danger" id="iTind"><b>TINDAKAN</b></a>
    </li>
</ul>
<div class="card shadow mb-4" id="dTind">
    <!-- Card Header - Accordion -->
    <div class="d-block card-header py-1 bg bg-info">
        <h3 id="DaftarTindakanSection" class="m-0 font-weight-bold text-dark text-center">ADD JENIS TINDAKAN</h3>
    </div>
    <div class="card-body card-body-hidden row d-flex justify-content-center">
        <div class="col-md-3 border border-primary p-2 mr-4 ">
            <div class=" d-flex justify-content-center z-3 position-sticky w-100 border-bottom-primary">
                <h5 class=""><b> Form Tambah Tindakan</b></h5>
            </div>
            <form class="p-2">
                <div class="form-grup p-2">
                    <div class="form-group col-auto">
                        <input type="text" id="nmTindakan" class="form-control border border-primary"
                            placeholder="Nama Tindakan" required>
                    </div>
                    <div class="form-group col-auto">
                        <input type="text" id="harga" class="form-control border border-primary"
                            placeholder="Harga Tindakan" required>
                    </div>
                    <div class="form-group col-auto">
                        <span><a id="addJenisTindakan" class="btn btn-success d-flex justify-content-center mx-2">+
                                Tambah
                                Tindakan</a>
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8 border border-primary">
            <div class="card-body card-body-hidden p-2">
                <div class=" d-flex justify-content-center z-3 position-sticky w-100 border-bottom-primary mb-3">
                    <h5 class=""><b> Daftar Tindakan</b></h5>
                </div>
                <table id="tindakan" class="table table-striped fs-6" style="width:100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="20px">Aksi</th>
                            <th width="20px">No</th>
                            <th width="40px">Nama Tindakan</th>
                            <th width="36px">Harga</th>
                        </tr>
                    </thead>
                </table>
                <div id="loadingSpinner" style="display: none;" class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-info" id="dStok">
    <div class="card-header">
        <h3 class="card-title">Stok Obat IGD</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal">
        <div class="card-body">
            <div class=" border border-black">
                <div class="card-body card-body-hidden p-2">
                    <table id="igdObat" class="table table-striped fs-6" style="width:100%" cellspacing="0">
                        <thead class="table-secondary table-sm">
                            <tr>
                                <th>Aksi</th>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Pabrikan</th>
                                <th>Sediaan</th>
                                <th>Sumber</th>
                                <th>Suplier</th>
                                <th>Tgl Beli</th>
                                <th>Tgl ED</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Jumlah</th>
                                <th>Keluar</th>
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
        <div id="loadingSpinner" style="display: none;" class="text-center">
            <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
        </div>
    </form>
</div>
<div class="card card-info" id="dBmhp">
    <div class="card-header">
        <h3 class="card-title">Stok Obat IGD</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal">
        <div class="card-body">
            <div class=" border border-black">
                <div class="card-body card-body-hidden p-2">
                    <table id="BMHP" class="table table-striped fs-6" style="width:100%" cellspacing="0">
                        <thead class="table-secondary table-sm">
                            <tr>
                                <th>Aksi</th>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Pabrikan</th>
                                <th>Sediaan</th>
                                <th>Sumber</th>
                                <th>Suplier</th>
                                <th>Tgl Beli</th>
                                <th>Tgl ED</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
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
        <div id="loadingSpinner" style="display: none;" class="text-center">
            <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
        </div>
    </form>
</div>
